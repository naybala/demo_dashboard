<?php

namespace BasicDashboard\Web\Dashboard\Controllers;

use BasicDashboard\Web\Common\BaseController;
use BasicDashboard\Web\Dashboard\Services\DashboardService;

class DashboardController extends BaseController
{
    public function __construct(private DashboardService $dashboardService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        return $this->dashboardService->index($request->all());
    }
}