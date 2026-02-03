<?php

namespace App\View\Composers;

use BasicDashboard\Foundations\Domain\Packages\Package;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

final class GroupComposer
{
    public function compose(View $view)
    {
        $view->with('viewGroups', \BasicDashboard\Foundations\Domain\Groups\Group::query()
            ->pluck('name', 'id'));
    }
}