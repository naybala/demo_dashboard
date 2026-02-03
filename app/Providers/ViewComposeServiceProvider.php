<?php
namespace App\Providers;

use App\View\Composers\CategoryComposer;
use App\View\Composers\CommuneComposer;
use App\View\Composers\DistrictComposer;
use App\View\Composers\GroupComposer;
use App\View\Composers\MapPriceRegionComposer;
use App\View\Composers\PackageComposer;
use App\View\Composers\PropertyTypeComposer;
use App\View\Composers\ProvinceComposer;
use App\View\Composers\RoleComposer;
use App\View\Composers\UserListComposer;
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

        //District
        View::composer(
            [
                'admin.listing.index', 'admin.district.index', 'admin.district.create', 'admin.district.edit', 'admin.listing.create', 'admin.listing.edit',
                'admin.user.create', 'admin.user.edit', 'admin.user.profile',
            ],
            ProvinceComposer::class
        );

        View::composer(
            [
                'admin.commune.create',
                'admin.commune.edit',
                'admin.commune.index',
            ],
            DistrictComposer::class
        );

        View::composer(
            [
                'admin.village.create',
                'admin.village.edit',
                'admin.village.index',
            ],
            CommuneComposer::class
        );

        View::composer(
            [
                'admin.listing.create', 'admin.listing.edit', 'admin.listing.index',
            ],
            PropertyTypeComposer::class
        );

        View::composer(
            [
                'admin.article.create',
                'admin.article.edit',
            ],
            CategoryComposer::class
        );

        View::composer(
            [
                'admin.order.index',
                'admin.article.create',
                'admin.article.edit',
                'admin.user-package.create',
                'admin.user-package.index',
                'admin.user-trial.index',
                'admin.user-trial.create',
                'admin.user-trial.edit',
            ],
            UserListComposer::class
        );

        View::composer(
            [
                'admin.package.index',
                'admin.package.create',
                'admin.package.edit',
                'admin.order.index',
                'admin.user-package.index',
                'admin.user-trial.create',
                'admin.user-trial.index',
                'admin.user-trial.edit',
            ],
            MapPriceRegionComposer::class
        );

        View::composer(
            [
                'admin.order.index',
                'admin.user-package.create',
                'admin.user-package.index',
            ],
            PackageComposer::class
        );

        View::composer(
            [
                'admin.user.create',
                'admin.user.edit',
            ],
            GroupComposer::class
        );

    }
}
