<?php

use Bepsvpt\SecureHeaders\SecureHeaders;
use PHPUnit\Framework\TestCase;

class SecureHeadersTest extends TestCase
{
    /**
     * @var string
     */
    protected $configPath = __DIR__.'/../config/secure-headers.php';

    public function test_send_headers()
    {
        SecureHeaders::fromFile($this->configPath)->send();

        $headers = xdebug_get_headers();

        $this->assertContains('X-Content-Type-Options: nosniff', $headers);
        $this->assertContains('Referrer-Policy: no-referrer', $headers);
    }

    public function test_disable_header()
    {
        $config = require $this->configPath;

        $config['x-download-options'] = null;

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArrayHasKey('X-Frame-Options', $headers);
        $this->assertArrayNotHasKey('X-Download-Options', $headers);
    }

    public function test_load_from_file()
    {
        $headers = SecureHeaders::fromFile($this->configPath)->headers();

        $this->assertArrayHasKey('Content-Security-Policy', $headers);
        $this->assertArrayHasKey('X-XSS-Protection', $headers);
    }

    public function test_file_not_found()
    {
        $this->expectException(InvalidArgumentException::class);

        SecureHeaders::fromFile(__DIR__.'/not-found');
    }

    public function test_nonce_value_always_the_same()
    {
        $nonce = SecureHeaders::nonce();

        $this->assertSame($nonce, SecureHeaders::nonce());

        $this->assertSame(SecureHeaders::nonce(), SecureHeaders::nonce());
    }

    public function test_csp_script_auto_generated_nonce()
    {
        $config = require $this->configPath;

        $config['csp']['script-src']['add-generated-nonce'] = true;

        $headers = (new SecureHeaders($config))->headers();

        $this->assertContains(SecureHeaders::nonce(), $headers['Content-Security-Policy']);
    }

    public function test_csp_style_auto_generated_nonce()
    {
        $config = require $this->configPath;

        $config['csp']['style-src']['add-generated-nonce'] = true;

        $headers = (new SecureHeaders($config))->headers();

        $this->assertContains(SecureHeaders::nonce(), $headers['Content-Security-Policy']);
    }

    public function test_custom_csp()
    {
        $config = require $this->configPath;

        $config['custom-csp'] = '';

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArrayNotHasKey('Content-Security-Policy', $headers);

        $config['custom-csp'] = 'apple';

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArraySubset([
            'Content-Security-Policy' => 'apple',
        ], $headers, true);
    }

    public function test_hpkp()
    {
        $config = require $this->configPath;

        $config['hpkp']['hashes'] = [
            '5feceb66ffc86f38d952786c6d696c79c2dbc239dd4e91b46729d73a27fb57e9',
            '6b86b273ff34fce19d6b804eff5a3f5747ada4eaa22f1d49c01e52ddb7875b4b',
        ];

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArrayHasKey('Public-Key-Pins', $headers);
    }

    public function test_hsts()
    {
        $config = require $this->configPath;

        $config['hsts']['enable'] = true;
        $config['hsts']['include-sub-domains'] = true;

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArraySubset([
            'Strict-Transport-Security' => 'max-age=15552000; includeSubDomains; preload',
        ], $headers, true);
    }
}
