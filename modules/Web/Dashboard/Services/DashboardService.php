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
     * Display a listing of the resource.
     */
    public function index(array $filters = [])
    {        
        $stats = $this->getDashboardStats($filters);
        return view('admin.dashboard.index', compact('stats', 'filters'));
    }

    public function getDashboardStats(array $filters): array
    {
        $startDate = $filters['start_date'] ?? null;
        $endDate = $filters['end_date'] ?? null;

        $productCount = OwnProduct::count();

        // Product distribution by category (Donut Chart)
        $productDistribution = OwnProduct::join('categories', 'own_products.category_id', '=', 'categories.id')
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

   
}
