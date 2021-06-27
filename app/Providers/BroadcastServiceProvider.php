<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::routes(['middleware' => ['web','auth:web,guest'] ]);//(1)

        require base_path('routes/channels.php');
    }
}

/**
 * Note
 */
//(1) broadcast multiguard: https://stackoverflow.com/a/51007381/11297747
