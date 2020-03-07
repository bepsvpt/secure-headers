<?php

namespace Bepsvpt\SecureHeaders;

use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application;

class SecureHeadersServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app instanceof Application) {
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
                $this->configPath() => config_path('secure-headers.php'),
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
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->configPath(), 'secure-headers');
    }

    /**
     * Get config file path.
     *
     * @return string
     */
    protected function configPath(): string
    {
        return __DIR__ . '/../config/secure-headers.php';
    }
}
