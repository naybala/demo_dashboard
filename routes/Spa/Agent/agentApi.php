<?php

use BasicDashboard\Spa\Agents\Controllers\AgentController;
use Illuminate\Support\Facades\Route;

Route::controller(AgentController::class)->group(function (): void {
    Route::post('/agent-listing', 'index');
});
