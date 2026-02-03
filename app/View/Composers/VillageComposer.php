<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class VillageComposer
{
    public function __construct()
    {
        //
    }
    
    public function compose(View $view)
    {
        $villages = Cache::flexible('viewVillages',[60,80],function(){
            return DB::table('villages')->pluck('name_en','code');
        });
        $view->with('viewVillages',$villages);
    }
}