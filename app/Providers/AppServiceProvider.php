<?php

namespace App\Providers;

use App\MyFacades\MyDatabaseManager;
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
        $this->app->singleton('db', function ($app) {
            return new MyDatabaseManager($app, $app['db.factory']);
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
