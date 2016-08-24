<?php

class MiddlewareTest extends PHPUnit_Framework_TestCase
{
    protected function copy($filename)
    {
        copy(__DIR__."/config/{$filename}.php", __DIR__.'/config/security-header.php');
    }

    protected function response()
    {
        $middleware = new Bepsvpt\LaravelSecurityHeader\SecurityHeaderMiddleware;

        return $middleware->handle(new Illuminate\Http\Request, function () {
            return new Illuminate\Http\Response;
        });
    }

    public function test_default_config()
    {
        $this->copy('default');

        $response = $this->response();

        $this->assertNotNull($response->headers->get('Content-Security-Policy'));
        $this->assertSame('nosniff', $response->headers->get('X-Content-Type-Options'));
        $this->assertSame('sameorigin', $response->headers->get('X-Frame-Options'));
        $this->assertSame('1; mode=block', $response->headers->get('X-XSS-Protection'));
    }

    public function test_hpkp_is_empty()
    {
        $this->copy('hpkp_empty');

        $response = $this->response();

        $this->assertNull($response->headers->get('Public-Key-Pins'));
    }

    public function test_hsts_is_enable()
    {
        $this->copy('hsts_enable');

        $response = $this->response();

        $this->assertNotNull($response->headers->get('Strict-Transport-Security'));
        $this->assertContains('includeSubDomains', $response->headers->get('Strict-Transport-Security'));
    }

    public function test_custom_csp()
    {
        $this->copy('custom_csp');

        $response = $this->response();

        $this->assertSame('laravel-security-header', $response->headers->get('Content-Security-Policy'));
    }
}
