<?php

class MiddlewareTest extends PHPUnit_Framework_TestCase
{
    private $config;

    public function setUp()
    {
        parent::setUp();

        @unlink(__DIR__.'/config/security-header.json');

        $this->config = json_decode(file_get_contents(__DIR__.'/config/default.json'), true);
    }

    protected function saveConfig()
    {
        file_put_contents(__DIR__.'/config/security-header.json', json_encode($this->config));
    }

    protected function response()
    {
        $middleware = new Bepsvpt\LaravelSecurityHeader\SecurityHeaderMiddleware;

        return $middleware->handle(new Illuminate\Http\Request, function () {
            return new Illuminate\Http\Response;
        });
    }

    public function test_using_default_config_file_if_config_file_not_found()
    {
        $response = $this->response();

        $this->assertNotNull($response->headers->get('Content-Security-Policy'));
        $this->assertNotNull($response->headers->get('X-Content-Type-Options'));
        $this->assertNotNull($response->headers->get('X-Frame-Options'));
        $this->assertNotNull($response->headers->get('X-XSS-Protection'));
    }

    public function test_hpkp_is_empty()
    {
        $this->config['hpkp']['hashes'] = [];
        $this->saveConfig();

        $response = $this->response();

        $this->assertNull($response->headers->get('Public-Key-Pins'));
    }

    public function test_hsts_is_enable()
    {
        $this->config['hsts']['enable'] = true;
        $this->config['hsts']['include_sub_domains'] = true;
        $this->saveConfig();

        $response = $this->response();

        $this->assertNotNull($response->headers->get('Strict-Transport-Security'));
        $this->assertContains('includeSubDomains', $response->headers->get('Strict-Transport-Security'));
    }
}
