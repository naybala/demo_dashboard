<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

final class UnitComposer
{
    public function compose(View $view)
    {
        $view->with('viewUnits',DB::table('units')->pluck('name','id'));
    }
}