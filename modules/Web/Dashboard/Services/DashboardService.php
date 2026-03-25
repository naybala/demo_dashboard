<?php
namespace BasicDashboard\Web\Dashboard\Services;

use BasicDashboard\Foundations\Domain\DailyIncomes\DailyIncome;
use BasicDashboard\Foundations\Domain\OwnProducts\OwnProduct;
use BasicDashboard\Web\Common\BaseController;
use Carbon\Carbon;
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
        return [
            'stats' => [],
            'monthly_revenue' => [],
            'daily_revenue' => [],
            'filters' => []
        ];
    }

   
}
