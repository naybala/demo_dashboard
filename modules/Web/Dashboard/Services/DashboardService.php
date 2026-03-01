<?php
namespace BasicDashboard\Web\Dashboard\Services;

use BasicDashboard\Foundations\Domain\DailyIncomes\DailyIncome;
use BasicDashboard\Foundations\Domain\OwnProducts\OwnProduct;
use BasicDashboard\Web\Common\BaseController;
use Illuminate\Support\Facades\DB;


class DashboardService extends BaseController
{
    public function __construct(
    ) {
    }

    /**
     * Get dashboard data.
     */
    public function getDashboardData(array $filters = []): array
    {        
        $stats = $this->getDashboardStats($filters);
        $monthlyRevenue = $this->getMonthlyRevenueData($filters);
        return [
            'stats' => $stats,
            'monthly_revenue' => $monthlyRevenue,
            'filters' => $filters
        ];
    }

    public function getDashboardStats(array $filters): array
    {
        $startDate = $filters['start_date'] ?? null;
        $endDate = $filters['end_date'] ?? null;

        $productCount = OwnProduct::count();

        // Product distribution by category (Donut Chart)
        $productDistribution = OwnProduct::join('categories', 'own_products.category_id', '=', 'categories.id')
            ->where('categories.is_show', 0)
            ->select('categories.name as label', DB::raw('count(*) as value'))
            ->groupBy('categories.id', 'categories.name')
            ->get();

        $query = DailyIncome::query();

        if ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        }

        // Clone query for stats and sales distribution
        $statsQuery = clone $query;
        $salesQuery = clone $query;

        $incomeStats = $statsQuery->selectRaw('
            SUM(amount) as total_amount,
            SUM(price) as total_price,
            SUM(investment) as total_investment,
            SUM(profit) as total_profit
        ')->first();

        // Sales distribution by category (Pie Chart)
        $salesDistribution = $salesQuery->join('own_products', 'daily_incomes.own_product_id', '=', 'own_products.id')
            ->join('categories', 'own_products.category_id', '=', 'categories.id')
            ->select('categories.name as label', DB::raw('SUM(daily_incomes.price) as value'))
            ->groupBy('categories.id', 'categories.name')
            ->get();

        return [
            'total_products' => $productCount,
            'total_amount' => $incomeStats->total_amount ?? 0,
            'total_price' => $incomeStats->total_price ?? 0,
            'total_investment' => $incomeStats->total_investment ?? 0,
            'total_profit' => $incomeStats->total_profit ?? 0,
            'product_distribution' => [
                'labels' => $productDistribution->pluck('label')->toArray(),
                'series' => $productDistribution->pluck('value')->toArray(),
            ],
            'sales_distribution' => [
                'labels' => $salesDistribution->pluck('label')->toArray(),
                'series' => $salesDistribution->pluck('value')->map(fn($v) => (float)$v)->toArray(),
            ],
        ];
    }

    public function getMonthlyRevenueData(array $filters = []): array
    {
        $year = $filters['year'] ?? null;
        
        if ($year) {
            $startDate = \Carbon\Carbon::createFromDate($year, 1, 1)->startOfDay();
            $endDate = \Carbon\Carbon::createFromDate($year, 12, 31)->endOfDay();
        } else {
            $startDate = now()->subMonths(11)->startOfMonth();
            $endDate = now()->endOfDay();
        }

        $monthlyData = DailyIncome::select(
            DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
            DB::raw('SUM(price) as total_revenue')
        )
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $labels = [];
        $seriesData = [];

        if ($year) {
            // Jan to Dec for the selected year
            for ($m = 1; $m <= 12; $m++) {
                $monthStr = sprintf('%04d-%02d', $year, $m);
                $labels[] = \Carbon\Carbon::createFromDate($year, $m, 1)->format('M');

                $match = $monthlyData->firstWhere('month', $monthStr);
                $seriesData[] = $match ? (float)$match->total_revenue : 0;
            }
        } else {
            // Last 12 months
            for ($i = 11; $i >= 0; $i--) {
                $month = now()->subMonths($i)->format('Y-m');
                $labels[] = now()->subMonths($i)->format('M');

                $match = $monthlyData->firstWhere('month', $month);
                $seriesData[] = $match ? (float)$match->total_revenue : 0;
            }
        }

        // Available years for filter (from DailyIncome dates)
        $availableYears = DailyIncome::selectRaw('YEAR(date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
            
        // Ensure current year is always there
        if (!in_array(now()->year, $availableYears)) {
            array_unshift($availableYears, now()->year);
        }

        return [
            'labels' => $labels,
            'series' => [
                [
                    'name' => 'Revenue',
                    'data' => $seriesData,
                ],
            ],
            'available_years' => $availableYears,
            'selected_year' => $year
        ];
    }
}
