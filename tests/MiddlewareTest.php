<?php

namespace Bepsvpt\Tests\SecureHeaders;

use Bepsvpt\SecureHeaders\SecureHeadersMiddleware;
use Bepsvpt\SecureHeaders\SecureHeadersServiceProvider;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Orchestra\Testbench\TestCase;

class MiddlewareTest extends TestCase
{
    /**
     * Wrapper laravel response for different version.
     *
     * @var string
     */
    protected $_response = 'baseResponse';

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        switch (substr($this->app->version(), 0, 3)) {
            case '5.1':
            case '5.2':
            case '5.3':
                $this->_response = 'response';
        }
    }

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
            SecureHeadersServiceProvider::class,
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
        $app->make(HttpKernel::class)->pushMiddleware(SecureHeadersMiddleware::class);
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
