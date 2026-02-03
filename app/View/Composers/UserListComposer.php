<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class UserListComposer
{
    public function __construct()
    {
        //
    }
    
    public function compose(View $view)
    {
        $users = Cache::flexible('viewUsers',[60,80],function(){
            return DB::table('users')->pluck('fullname','id');
        });
        $view->with('viewUsers',$users);
    }
}