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

                foreach ($openApi->paths as $path) {
                    foreach ($path->operations as $operation) {
                        if ($operationId = $operation->operationId) {
                            $parts = explode('.', $operationId);
                            $operation->operationId = end($parts);
                        }
                    }
                }
            });

        Scramble::resolveTagsUsing(function (\Dedoc\Scramble\Support\RouteInfo $routeInfo) {
            $uri = $routeInfo->route->uri();
            $segments = explode('/', trim($uri, '/'));
            
            $module = null;
            $moduleIndex = -1;
            foreach ($segments as $index => $segment) {
                if (in_array(strtolower($segment), ['mobile', 'spa'])) {
                    $module = ucfirst($segment);
                    $moduleIndex = $index;
                    break;
                }
            }

            if ($module && isset($segments[$moduleIndex + 1])) {
                $resource = ucfirst($segments[$moduleIndex + 1]);
                return ["$module / $resource"];
            }

            return $module ? [$module] : null;
        });
    }

}
