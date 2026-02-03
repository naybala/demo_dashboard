<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ProvinceComposer
{
    public function __construct()
    {
        //
    }
    
    public function compose(View $view)
    {
        $view->with('viewProvinces',DB::table('provinces')->pluck('name_en','code'));
    }
}