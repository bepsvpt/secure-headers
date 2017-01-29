<?php

class MiddlewareTest extends Orchestra\Testbench\TestCase
{
    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            Bepsvpt\SecureHeaders\SecureHeadersServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app->make(Illuminate\Contracts\Http\Kernel::class)->pushMiddleware(Bepsvpt\SecureHeaders\SecureHeadersMiddleware::class);
    }

    public function test_middleware()
    {
        $response = $this->get('/');

        $response->assertHeader('x-frame-options');
        $response->assertHeader('content-security-policy');
    }
}
