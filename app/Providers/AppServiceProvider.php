<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if(config('app.force_schema')==true){
            \URL::forceScheme('https');
        }
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //To Test Log for database query
        if(config('services.settings.DB_LISTENING')){
            DB::listen(function ($query) {
                \Log::info($query->sql, ['bindings' => $query->bindings, 'time' => $query->time]);
            });
        }
        if(config('services.settings.PREVENT_DB_FRESH')){
            DB::prohibitDestructiveCommands();
        }

        Scramble::configure()
        ->withDocumentTransformers(function (OpenApi $openApi) {
            $openApi->secure(
                SecurityScheme::http('bearer')
            );
        });
    }

}
