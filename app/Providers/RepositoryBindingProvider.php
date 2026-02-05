<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use BasicDashboard\Foundations\Domain\Roles\Repositories\Eloquent\RoleRepository;
use BasicDashboard\Foundations\Domain\Roles\Repositories\RoleRepositoryInterface;
use BasicDashboard\Foundations\Domain\Users\Repositories\Eloquent\UserRepository;
use BasicDashboard\Foundations\Domain\Users\Repositories\UserRepositoryInterface;
use BasicDashboard\Foundations\Domain\Audits\Repositories\AuditRepositoryInterface;
use BasicDashboard\Foundations\Domain\Audits\Repositories\Eloquent\AuditRepository;
use BasicDashboard\Foundations\Domain\Categories\Repositories\CategoryRepositoryInterface;
use BasicDashboard\Foundations\Domain\Categories\Repositories\Eloquent\CategoryRepository;

class RepositoryBindingProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AuditRepositoryInterface::class, AuditRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
