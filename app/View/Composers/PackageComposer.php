<?php

namespace App\View\Composers;

use BasicDashboard\Foundations\Domain\Packages\Package;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

final class PackageComposer
{
    public function compose(View $view)
    {
        $view->with('viewPackages', Package::query()
            ->select(DB::raw("id, CONCAT(name, ' - ', duration, ' ', type) as package_name"))
            ->orderBy('id', 'desc')
            ->pluck('package_name', 'id'));
    }
}