<?php

namespace App\Providers;

use App\MyFacades\MyDatabaseManager;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Http\View\Composers\SizeColorComposer;
use Illuminate\Support\Str;
use App\Mixin\StrMixin;

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
        View::composer(['cart','product'], SizeColorComposer::class);

        Str::mixin( new StrMixin());
    }
}
