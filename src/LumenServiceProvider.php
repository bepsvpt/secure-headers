<?php

namespace Bepsvpt\SecureHeaders;

class LumenServiceProvider extends SecureHeadersServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->configure('secure-headers');

        $this->app->middleware([
            SecureHeadersMiddleware::class,
        ]);
    }
}
