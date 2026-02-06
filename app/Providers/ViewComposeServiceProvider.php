<?php
namespace App\Providers;
use App\View\Composers\CategoryComposer;
use App\View\Composers\RoleComposer;
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
        //User
        View::composer('admin.user.create', RoleComposer::class);
        View::composer('admin.user.edit', RoleComposer::class);

        //Product
        View::composer('admin.product.create', CategoryComposer::class);
        View::composer('admin.product.edit', CategoryComposer::class);


    }
}
