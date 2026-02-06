<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Dedoc\Scramble\Support\RouteInfo;



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
                $openApi->secure(SecurityScheme::http('bearer'));

                foreach ($openApi->paths as $path) {
                    foreach ($path->operations as $operation) {

                        $route = $operation->route ?? null;

                        if ($route) {
                            $uri = $route->uri();
                            $segments = explode('/', trim($uri, '/'));

                            foreach ($segments as $i => $segment) {
                                if (in_array(strtolower($segment), ['mobile', 'spa']) && isset($segments[$i + 1])) {

                                    $module = strtolower($segment);
                                    $resource = strtolower($segments[$i + 1]);
                                    $action = strtolower($route->getActionMethod());

                                    //  UNIQUE operationId
                                    $operation->operationId = "{$module}.{$resource}.{$action}";
                                    break;
                                }
                            }
                        }
                    }
                }
            })


            ->withOperationTransformers(function ($operation, RouteInfo $routeInfo) {
                $uri = $routeInfo->route->uri();
                $segments = explode('/', trim($uri, '/'));

                foreach ($segments as $i => $segment) {
                    if (in_array(strtolower($segment), ['mobile', 'spa']) && isset($segments[$i + 1])) {
                        $resource = ucfirst($segments[$i + 1]);
                        $action = strtolower($routeInfo->route->getActionMethod());

                        $operation->summary = "{$resource}.{$action}";
                        return;
                    }
                }
        });

        Scramble::resolveTagsUsing(function (RouteInfo $routeInfo) {
            $uri = $routeInfo->route->uri();
            $segments = explode('/', trim($uri, '/'));

            foreach ($segments as $i => $segment) {
                if (in_array(strtolower($segment), ['mobile', 'spa']) && isset($segments[$i + 1])) {
                    $module = ucfirst($segment);
                    return [$module]; // keep ONLY parent group
                }
            }

            return null;
        });


    }

}
