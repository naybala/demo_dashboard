<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use BasicDashboard\Foundations\Domain\Pages\Repositories\Eloquent\PageRepository;
use BasicDashboard\Foundations\Domain\Pages\Repositories\PageRepositoryInterface;
use BasicDashboard\Foundations\Domain\Roles\Repositories\Eloquent\RoleRepository;
use BasicDashboard\Foundations\Domain\Roles\Repositories\RoleRepositoryInterface;
use BasicDashboard\Foundations\Domain\Users\Repositories\Eloquent\UserRepository;
use BasicDashboard\Foundations\Domain\Users\Repositories\UserRepositoryInterface;
use BasicDashboard\Foundations\Domain\Audits\Repositories\AuditRepositoryInterface;
use BasicDashboard\Foundations\Domain\Audits\Repositories\Eloquent\AuditRepository;
use BasicDashboard\Foundations\Domain\Groups\Repositories\Eloquent\GroupRepository;
use BasicDashboard\Foundations\Domain\Groups\Repositories\GroupRepositoryInterface;
use BasicDashboard\Foundations\Domain\Orders\Repositories\Eloquent\OrderRepository;
use BasicDashboard\Foundations\Domain\Orders\Repositories\OrderRepositoryInterface;
use BasicDashboard\Foundations\Domain\Articles\Repositories\ArticleRepositoryInterface;
use BasicDashboard\Foundations\Domain\Articles\Repositories\Eloquent\ArticleRepository;
use BasicDashboard\Foundations\Domain\Communes\Repositories\CommuneRepositoryInterface;
use BasicDashboard\Foundations\Domain\Communes\Repositories\Eloquent\CommuneRepository;
use BasicDashboard\Foundations\Domain\Listings\Repositories\Eloquent\ListingRepository;
use BasicDashboard\Foundations\Domain\Listings\Repositories\ListingRepositoryInterface;
use BasicDashboard\Foundations\Domain\Packages\Repositories\Eloquent\PackageRepository;
use BasicDashboard\Foundations\Domain\Packages\Repositories\PackageRepositoryInterface;
use BasicDashboard\Foundations\Domain\Partners\Repositories\Eloquent\PartnerRepository;
use BasicDashboard\Foundations\Domain\Partners\Repositories\PartnerRepositoryInterface;
use BasicDashboard\Foundations\Domain\Settings\Repositories\Eloquent\SettingRepository;
use BasicDashboard\Foundations\Domain\Settings\Repositories\SettingRepositoryInterface;
use BasicDashboard\Foundations\Domain\Villages\Repositories\Eloquent\VillageRepository;
use BasicDashboard\Foundations\Domain\Villages\Repositories\VillageRepositoryInterface;
use BasicDashboard\Foundations\Domain\Districts\Repositories\DistrictRepositoryInterface;
use BasicDashboard\Foundations\Domain\Districts\Repositories\Eloquent\DistrictRepository;
use BasicDashboard\Foundations\Domain\MapPrices\Repositories\Eloquent\MapPriceRepository;
use BasicDashboard\Foundations\Domain\MapPrices\Repositories\MapPriceRepositoryInterface;
use BasicDashboard\Foundations\Domain\Provinces\Repositories\Eloquent\ProvinceRepository;
use BasicDashboard\Foundations\Domain\Provinces\Repositories\ProvinceRepositoryInterface;
use BasicDashboard\Foundations\Domain\Categories\Repositories\CategoryRepositoryInterface;
use BasicDashboard\Foundations\Domain\Categories\Repositories\Eloquent\CategoryRepository;
use BasicDashboard\Foundations\Domain\GroupUsers\Repositories\Eloquent\GroupUserRepository;
use BasicDashboard\Foundations\Domain\GroupUsers\Repositories\GroupUserRepositoryInterface;
use BasicDashboard\Foundations\Domain\UserGuides\Repositories\Eloquent\UserGuideRepository;
use BasicDashboard\Foundations\Domain\UserGuides\Repositories\UserGuideRepositoryInterface;
use BasicDashboard\Foundations\Domain\UserTrials\Repositories\Eloquent\UserTrialRepository;
use BasicDashboard\Foundations\Domain\UserTrials\Repositories\UserTrialRepositoryInterface;
use BasicDashboard\Foundations\Domain\BankPartners\Repositories\BankPartnerRepositoryInterface;
use BasicDashboard\Foundations\Domain\BankPartners\Repositories\Eloquent\BankPartnerRepository;
use BasicDashboard\Foundations\Domain\UserPackages\Repositories\Eloquent\UserPackageRepository;
use BasicDashboard\Foundations\Domain\UserPackages\Repositories\UserPackageRepositoryInterface;
use BasicDashboard\Foundations\Domain\MapPriceBanks\Repositories\Eloquent\MapPriceBankRepository;
use BasicDashboard\Foundations\Domain\MapPriceBanks\Repositories\MapPriceBankRepositoryInterface;
use BasicDashboard\Foundations\Domain\PropertyTypes\Repositories\Eloquent\PropertyTypeRepository;
use BasicDashboard\Foundations\Domain\PropertyTypes\Repositories\PropertyTypeRepositoryInterface;
use BasicDashboard\Foundations\Domain\MapPriceRegions\Repositories\Eloquent\MapPriceRegionRepository;
use BasicDashboard\Foundations\Domain\MapPriceRegions\Repositories\MapPriceRegionRepositoryInterface;
use BasicDashboard\Foundations\Domain\MapPriceKmzFiles\Repositories\Eloquent\MapPriceKmzFileRepository;
use BasicDashboard\Foundations\Domain\MapPriceKmzFiles\Repositories\MapPriceKmzFileRepositoryInterface;

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
        $this->app->bind(PageRepositoryInterface::class, PageRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(PropertyTypeRepositoryInterface::class, PropertyTypeRepository::class);
        $this->app->bind(ProvinceRepositoryInterface::class, ProvinceRepository::class);
        $this->app->bind(DistrictRepositoryInterface::class, DistrictRepository::class);
        $this->app->bind(CommuneRepositoryInterface::class, CommuneRepository::class);
        $this->app->bind(VillageRepositoryInterface::class, VillageRepository::class);
        $this->app->bind(ListingRepositoryInterface::class, ListingRepository::class);
        $this->app->bind(BankPartnerRepositoryInterface::class, BankPartnerRepository::class);
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(PartnerRepositoryInterface::class, PartnerRepository::class);
        $this->app->bind(MapPriceRepositoryInterface::class, MapPriceRepository::class);
        $this->app->bind(MapPriceRegionRepositoryInterface::class, MapPriceRegionRepository::class);
        $this->app->bind(PackageRepositoryInterface::class, PackageRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(UserPackageRepositoryInterface::class, UserPackageRepository::class);
        $this->app->bind(MapPriceBankRepositoryInterface::class, MapPriceBankRepository::class);
        $this->app->bind(MapPriceKmzFileRepositoryInterface::class, MapPriceKmzFileRepository::class);
        $this->app->bind(UserTrialRepositoryInterface::class, UserTrialRepository::class);
        $this->app->bind(UserGuideRepositoryInterface::class, UserGuideRepository::class);
        $this->app->bind(GroupRepositoryInterface::class, GroupRepository::class);
        $this->app->bind(GroupUserRepositoryInterface::class, GroupUserRepository::class);
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
