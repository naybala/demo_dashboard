<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class CommuneComposer
{
    public function __construct()
    {
        //
    }
    
    public function compose(View $view)
    {
        $communes = Cache::flexible('viewCommunes',[60,80],function(){
            return DB::table('communes')->pluck('name_en','code');
        });
        $view->with('viewCommunes',$communes);
    }
}