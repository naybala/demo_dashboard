<?php
namespace BasicDashboard\Foundations\Domain\Users\Repositories;

use BasicDashboard\Foundations\Domain\Base\Repositories\BaseRepositoryInterface;
use BasicDashboard\Foundations\Domain\Users\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function getUserList($params): LengthAwarePaginator;
    public function filterUser(array $params): Builder | User;

}
