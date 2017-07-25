<?php

namespace Bepsvpt\SecureHeaders;

use Illuminate\Support\ServiceProvider;

class SecureHeadersServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app instanceof \Laravel\Lumen\Application) {
            $this->bootLumen();
        } else {
            $this->bootLaravel();
        }
    }

    /**
     * Bootstrap laravel application events.
     *
     * @return void
     */
    protected function bootLaravel()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/secure-headers.php' => config_path('secure-headers.php'),
            ], 'config');
        }
    }

    /**
     * Bootstrap lumen application events.
     *
     * @return void
     */
    protected function bootLumen()
    {
        $this->app->configure('secure-headers');

        $this->app->middleware([
            SecureHeadersMiddleware::class,
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/secure-headers.php', 'secure-headers'
        );
    }
}
