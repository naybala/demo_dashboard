<?php
namespace BasicDashboard\Web\Dashboard\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function __construct(
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {        
        return view('admin.dashboard.index');
    }

    protected function getOnlineUsers($groupId): Collection
    {
        $activeUsers = $this->fetchOnlineUsers($groupId);

        $formatUsers = $activeUsers->each(function ($user): void {
            $user->last_activity = $this->formatLastActivity($user->last_activity);
            $user->user_agent    = $this->formatuserAgent($user->user_agent);
        });
        return $formatUsers;
    }

    /**
     * Fetch online users
     * @return \Illuminate\Support\Collection
     */

    public function fetchOnlineUsers($groupId): Collection
    {

        // Base query for online users
        $query = DB::table('sessions')
            ->join('users', 'sessions.user_id', '=', 'users.id')
            ->whereNotNull('sessions.user_id')
            ->select(
                'users.fullname',
                'sessions.last_activity',
                'sessions.user_agent',
                'sessions.ip_address'
            )
            ->distinct();

        // If user has a group, limit the results to users in that group
        if ($groupId) {
            $query->join('group_users', 'users.id', '=', 'group_users.user_id')
                ->where('group_users.group_id', $groupId);
        }

        return $query->limit(5)->get();
    }

    protected function formatLastActivity($timestamp): string
    {
        return $timestamp ? Carbon::parse($timestamp)->diffForHumans() : null;
    }

    public function formatuserAgent(string $userAgent): string
    {
        // Simplify user_agent (e.g., just show browser name)
        if (str_contains($userAgent, 'Chrome')) {
            return 'Chrome';
        } elseif (str_contains($userAgent, 'Safari') && ! str_contains($userAgent, 'Chrome')) {
            return 'Safari';
        } elseif (str_contains($userAgent, 'Firefox')) {
            return 'Firefox';
        } elseif (str_contains($userAgent, 'Edge')) {
            return 'Edge';
        } else {
            return 'Other';
        }
    }

}
