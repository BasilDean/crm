<?php

namespace App\Providers;

use App\Services\Localization\LocalizationService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ModularProvider extends ServiceProvider
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
        $modules = config('modular.modules');
        $path = config('modular.path');

        if ($modules) {
            Route::group([
                'prefix'=>LocalizationService::locale()
            ], function() use($modules, $path) {
                foreach ($modules as $mod => $submodules) {
                    foreach ($submodules as $key => $submodule) {
                        $relativePath = "/$mod/$submodule";

                        Route::middleware('web')
                            ->group(function () use($mod, $submodule, $path, $relativePath) {
                                $this->getRoutes($mod, $submodule, $path, $relativePath);
                            });

                        Route::middleware('api')
                            ->prefix('api')
                            ->group(function () use($mod, $submodule, $path, $relativePath) {
                                $this->getRoutes($mod, $submodule, $path, $relativePath, true);
                            });
                    }
               }
            });
        }

        $this->app['view']->addNamespace('Pub',base_path().'/resources/views/Pub');
        $this->app['view']->addNamespace('Admin',base_path().'/resources/views/Admin');
    }

    private function getRoutes(mixed $mod, mixed $submodule, string $path, string $relativePath, bool $isApi = false)
    {
        $routesPath = $path.$relativePath.'/Routes/' . ($isApi ? 'api' : 'web') . '.php';
        if (file_exists($routesPath)) {

            if ($mod != config('modular.groupWithoutPrefix') && !$isApi) {
                Route::group(
                    [
                        'prefix' => strtolower($mod),
                        'middleware' => $this->getMiddleware($mod)
                    ],
                    function () use ($mod, $submodule, $routesPath) {
                        Route::namespace("App\Modules\\$mod\\$submodule\Controllers")->group($routesPath);
                    }
                );
            }
            elseif (!$isApi) {
                Route::namespace("App\Modules\\$mod\\$submodule\Controllers")->middleware($this->getMiddleware($mod, $isApi ? 'api' : 'web'))->group($routesPath);
            }
            else {
                Route::group(
                    [
                        'prefix' => strtolower($mod),
                        'middleware' => $this->getMiddleware($mod, 'api')
                    ],
                    function() use ($mod, $submodule, $routesPath) {
                        Route::namespace("App\Modules\\$mod\\$submodule\Controllers")->group($routesPath);
                    }
                );
            }
        }
    }

    private function getMiddleware(mixed $mod, string $key = 'web') : array
    {
        $middleware = [];

        $config = config('modular.groupMiddleware');

        if (isset($config[$mod])) {
            if (array_key_exists($key, $config[$mod])) {
                $middleware = array_merge($middleware, $config[$mod][$key]);
            }
        }
        return $middleware;
    }

}
