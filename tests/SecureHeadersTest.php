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
        $this->assertContains('Referrer-Policy: strict-origin-when-cross-origin', $headers);
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
            ['algo' => 'sha256', 'hash' => 'apple'],
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
            'Strict-Transport-Security' => 'max-age=15552000; preload; includeSubDomains;',
        ], $headers, true);
    }
}
