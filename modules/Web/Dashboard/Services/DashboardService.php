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
            'stats' => [
                'total_students' => DB::table('students')->whereNull('deleted_at')->count(),
                'total_teachers' => DB::table('users')->where('user_type', 2)->whereNull('deleted_at')->count(),
                'total_classes'  => DB::table('classes')->whereNull('deleted_at')->count(),
            ],
            'filters' => $filters
        ];
    }

   
}
