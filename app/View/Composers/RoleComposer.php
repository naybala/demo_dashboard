<?php
namespace App\View\Composers;

use Auth;
use BasicDashboard\Foundations\Domain\GroupUsers\GroupUser;
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
        $authUserId   = Auth::id();
        $userHasGroup = GroupUser::where('user_id', $authUserId)->value('group_id');
        if ($userHasGroup) {
            $view->with('viewRoles', DB::table('roles')->where('name', 'like', '%Partner Bank%')->pluck('name', 'id'));
        } else {
            $view->with('viewRoles', DB::table('roles')->pluck('name', 'id'));
        }
    }
}
