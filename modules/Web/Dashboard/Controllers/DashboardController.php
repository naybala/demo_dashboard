<?php

namespace BasicDashboard\Web\Dashboard\Controllers;

use BasicDashboard\Web\Common\BaseController;
use BasicDashboard\Web\Dashboard\Services\DashboardService;

use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends BaseController
{
    public function __construct(private DashboardService $dashboardService)
    {
    }

    /**
     * Display the dashboard.
     */
    public function index(\Illuminate\Http\Request $request): Response
    {
        $data = $this->dashboardService->getDashboardData($request->all());
        return Inertia::render('Dashboard/Index', $data);
    }
}