<?php

namespace Bepsvpt\LaravelSecurityHeader;

use Illuminate\Support\ServiceProvider;

class SecurityHeaderServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__ . '/../config/security-header.php';

        $publishPath = config_path('security-header.php');

        $this->publishes([$configPath => $publishPath], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ . '/../config/security-header.php';

        $this->mergeConfigFrom($configPath, 'security-header');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['command.vendor.publish'];
    }
}
