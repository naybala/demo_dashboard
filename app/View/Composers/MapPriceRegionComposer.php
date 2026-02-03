<?php

namespace App\View\Composers;

use BasicDashboard\Foundations\Domain\MapPriceRegions\MapPriceRegion;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

final class MapPriceRegionComposer
{
    public function compose(View $view)
    {
        $view->with('viewMapPriceRegions',MapPriceRegion::pluck('name','id'));
    }
}