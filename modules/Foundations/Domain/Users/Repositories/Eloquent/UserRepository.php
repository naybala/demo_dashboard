<?php
namespace BasicDashboard\Foundations\Domain\Users\Repositories\Eloquent;

use BasicDashboard\Foundations\Domain\Base\Repositories\Eloquent\BaseRepository;
use BasicDashboard\Foundations\Domain\Users\Repositories\UserRepositoryInterface;
use BasicDashboard\Foundations\Domain\Users\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function filterUser(array $params): Builder | User
    {
        $status_text = [
            'active'   => 1,
            'inactive' => 2,
        ];

        $connection = $this->connection(true)->with(['roles']);

        if (! empty($params['keyword'])) {
            $keyword = strtolower($params['keyword']);

            $connection->where(function (Builder $query) use ($keyword, $status_text) {
                $query->where(function ($q) use ($keyword, $status_text) {
                    $q->where('fullname', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%")
                        ->orWhere('phone_number', 'like', "%{$keyword}%")
                        ->orWhere('role_marked', 'like', "%{$keyword}%");

                    // Optional: interpret 'active'/'inactive' text into status code
                    if (isset($status_text[$keyword])) {
                        $q->orWhere('status', $status_text[$keyword]);
                    } else {
                        $q->orWhere('status', 'like', "%{$keyword}%");
                    }
                });

                // Match roles.name
                $query->orWhereHas('roles', function ($roleQuery) use ($keyword) {
                    $roleQuery->where('name', 'like', "%{$keyword}%");
                });

                // Match groups.name (optional, if needed)
                $query->orWhereHas('groups', function ($groupQuery) use ($keyword) {
                    $groupQuery->where('name', 'like', "%{$keyword}%");
                });
            });
        }

        return $connection;
    }

    public function getUserList($params): LengthAwarePaginator
    {
        return $this->filterUser($params)
            ->orderByRaw('CASE WHEN created_at IS NULL THEN updated_at ELSE created_at END DESC')
            ->orderBy('id', 'desc')
            ->paginate(request()->paginate ?? config('numbers.paginate'));
    }

    public function getUserListByGroup($params, string $userId, string $groupId): LengthAwarePaginator
    {
        return $this->filterUser($params)
            ->whereHas('groups', function ($query) use ($groupId) {
                $query->where('groups.id', $groupId);
            })
            ->orderByRaw('CASE WHEN created_at IS NULL THEN updated_at ELSE created_at END DESC')
            ->orderBy('id', 'desc')
            ->paginate(request()->paginate ?? config('numbers.paginate'));
    }

    //Mobile Sections
    public function findByEmail($params): mixed
    {
        return $this->connection(true)->where('email', $params['email'])->where('status', true)->first();
    }

    public function getUserOneTap($params): mixed
    {
        return $this->connection(true)
            ->where('oauth_id', $params['oauth_id'])
            ->where('oauth_provider', $params['oauth_provider'])
            ->first();
    }

    //Spa Section
    public function getUserByEmail(string $email): mixed
    {
        return $this->connection(true)->where('email', $email)->first();
    }
    public function getUsersByFullNameOrPhoneNumber(string $fullName, int $limit): \Illuminate\Support\Collection
    {
        return $this->connection(true)
            ->whereAny(['fullname', 'phone_number'], 'LIKE', "%$fullName%")
            ->limit($limit)
            ->select('id', 'fullname', 'phone_number')->get();
    }

}
