<?php
namespace App\View\Composers;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RoleComposer
{
    public function __construct()
    {
        //
    }

    public function compose(View $view)
    {      
        $view->with('viewRoles', DB::table('roles')->pluck('name', 'id'));
    }
}
