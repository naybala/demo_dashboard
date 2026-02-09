<?php

namespace BasicDashboard\Web\Dashboard\Controllers;

use BasicDashboard\Web\Dashboard\Services\DashboardService;

class DashboardController
{
    public function __construct(private DashboardService $dashboardService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->dashboardService->index();
    }
}