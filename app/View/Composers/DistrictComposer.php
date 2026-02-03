<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class DistrictComposer
{
    public function __construct()
    {
        //
    }
    
    public function compose(View $view)
    {
        $view->with('viewDistricts',DB::table('districts')->pluck('name_en','code'));
    }
}