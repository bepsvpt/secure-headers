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
        $this->app['router']->get('/', function () {
            return 'Hello World!';
        });

        $response = $this->get('/');

        $response->assertHeader('x-frame-options');
        $response->assertHeader('content-security-policy');
    }

    public function test_binary_response()
    {
        $this->app['router']->get('/', function () {
            return response()->download(__DIR__.'/../README.md');
        });

        $response = $this->get('/');

        $this->assertFalse($response->headers->has('x-content-type-options'));
        $this->assertFalse($response->headers->has('content-security-policy'));
    }
}
