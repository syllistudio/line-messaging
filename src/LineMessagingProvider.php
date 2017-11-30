<?php

namespace Syllistudio\LineMessaging;

use Illuminate\Support\ServiceProvider;

class LineMessagingProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/config/line-messaging.php' => config_path('line-messaging.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/line-messaging.php', 'line-messaging');
    }
}
