<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

final class PropertyTypeComposer
{
    public function compose(View $view)
    {
        $view->with('viewPropertyTypes',DB::table('property_types')->orderBy('id','desc')->pluck('name','id'));
    }
}