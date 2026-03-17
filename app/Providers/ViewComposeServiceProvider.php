<?php
namespace App\Providers;
use App\View\Composers\CategoryComposer;
use App\View\Composers\OwnProductComposer;
use App\View\Composers\RoleComposer;
use App\View\Composers\UnitComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //User (Currently this logics are useless but later we will use it)
        // View::composer('admin.user.create', RoleComposer::class);
        // View::composer('admin.user.edit', RoleComposer::class);

    }
}
