<?php

namespace App\Providers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OnlineApplicationController;
use App\Http\Controllers\UserController;
use App\Repositories\OAuthClientRepository;
use App\Repositories\UserRepository;
use App\Services\OAuthClientService;
use App\Services\OnlineApplicationService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Http\Controllers\OnlineApplicationController', function ($app) {
            return new OnlineApplicationController($app->make(OnlineApplicationService::class), $app->make(OAuthClientService::class), $app->make(UserService::class));
        });

        $this->app->bind('App\Http\Controllers\UserController', function ($app) {
            return new UserController($app->make(UserService::class));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
