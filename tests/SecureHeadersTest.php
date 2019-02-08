<?php

namespace Bepsvpt\Tests\SecureHeaders;

use Bepsvpt\SecureHeaders\SecureHeaders;
use InvalidArgumentException;
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

    public function test_server_header()
    {
        $config = require $this->configPath;

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArrayNotHasKey('Server', $headers);

        $config['server'] = 'Example';

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArraySubset([
            'Server' => 'Example',
        ], $headers, true);
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

    public function test_feature_policy()
    {
        $config = require $this->configPath;

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArrayHasKey('Feature-Policy', $headers);

        // disable feature policy
        $config = require $this->configPath;

        $config['feature-policy']['enable'] = false;

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArrayNotHasKey('Feature-Policy', $headers);

        // ensure backward compatibility
        unset($config['feature-policy']);

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArrayNotHasKey('Feature-Policy', $headers);
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

    public function test_expect_ct()
    {
        $config = require $this->configPath;

        // only enable expect-ct
        $config['expect-ct']['enable'] = true;

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArraySubset([
            'Expect-CT' => 'max-age=2147483648',
        ], $headers, true);

        // add enforce flag
        $config['expect-ct']['enforce'] = true;

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArraySubset([
            'Expect-CT' => 'max-age=2147483648, enforce',
        ], $headers, true);

        // add report-uri flag
        $config['expect-ct']['report-uri'] = 'https://example.com/report-ct';

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArraySubset([
            'Expect-CT' => 'max-age=2147483648, enforce, report-uri="https://example.com/report-ct"',
        ], $headers, true);

        $config['expect-ct']['enforce'] = false;

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArraySubset([
            'Expect-CT' => 'max-age=2147483648, report-uri="https://example.com/report-ct"',
        ], $headers, true);

        // ensure backward compatibility
        unset($config['expect-ct']);

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArrayNotHasKey('Expect-CT', $headers);
    }

    public function test_clear_site_data()
    {
        $config = require $this->configPath;

        // enable Clear-Site-Data
        $config['clear-site-data']['enable'] = true;

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArraySubset([
            'Clear-Site-Data' => '"cache", "cookies", "storage", "executionContexts"',
        ], $headers, true);

        // disable cookie and executionContexts
        $config['clear-site-data']['cookies'] = false;
        $config['clear-site-data']['executionContexts'] = false;

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArraySubset([
            'Clear-Site-Data' => '"cache", "storage"',
        ], $headers, true);

        // disable cache and storage
        $config['clear-site-data']['cache'] = false;
        $config['clear-site-data']['storage'] = false;

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArrayNotHasKey('Clear-Site-Data', $headers);

        // use all
        $config['clear-site-data']['all'] = true;

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArraySubset([
            'Clear-Site-Data' => '"*"',
        ], $headers, true);

        // ensure backward compatibility
        unset($config['clear-site-data']);

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArrayNotHasKey('Clear-Site-Data', $headers);
    }
}
