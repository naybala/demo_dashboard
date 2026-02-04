<?php
namespace App\Providers;
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

       

        // View::composer(
        //     [
        //         'admin.article.create',
        //         'admin.article.edit',
        //     ],
        //     CategoryComposer::class
        // );  

    }
}
