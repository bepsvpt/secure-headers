<?php

namespace Bepsvpt\SecureHeaders\Tests;

use Bepsvpt\SecureHeaders\SecureHeadersMiddleware;
use Bepsvpt\SecureHeaders\SecureHeadersServiceProvider;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase;

final class MiddlewareTest extends TestCase
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
     * @param  Application  $app
     * @return array<string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            SecureHeadersServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        switch (substr($app->version(), 0, 3)) {
            case '5.1':
            case '5.2':
            case '5.3':
                $this->_response = 'response';
        }

        $app->make(HttpKernel::class)->pushMiddleware(SecureHeadersMiddleware::class); // @phpstan-ignore-line
    }

    public function test_middleware()
    {
        $this->app['router']->get('/', function () {
            return 'Hello World!';
        });

        $headers = $this->get('/')->{$this->_response}->headers->all();

        $this->assertArrayHasKey('x-frame-options', $headers);
    }

    public function test_binary_response()
    {
        $this->app['router']->get('/', function () {
            return response()->download(__DIR__.'/../README.md');
        });

        $headers = $this->get('/')->{$this->_response}->headers->all();

        $this->assertArrayHasKey(
            'x-content-type-options',
            $headers
        );

        $this->assertArrayHasKey(
            'permissions-policy',
            $headers
        );
    }
}
