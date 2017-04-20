<?php

class MiddlewareTest extends Orchestra\Testbench\TestCase
{
    /**
     * Wrapper laravel response for different version.
     *
     * @var string
     */
    protected $_response = 'baseResponse';

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

        $headers = $this->get('/')->{$this->_response}->headers->all();

        $this->assertArrayHasKey('x-frame-options', $headers);
        $this->assertArrayHasKey('content-security-policy', $headers);
    }

    public function test_binary_response()
    {
        $this->app['router']->get('/', function () {
            return response()->download(__DIR__.'/../README.md');
        });

        $headers = $this->get('/')->{$this->_response}->headers->all();

        $this->assertArrayHasKey('x-content-type-options', $headers);
        $this->assertArrayHasKey('content-security-policy', $headers);
    }
}
