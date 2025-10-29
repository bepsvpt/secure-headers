<?php

namespace Bepsvpt\SecureHeaders\Tests;

use Bepsvpt\SecureHeaders\SecureHeaders;
use InvalidArgumentException;

final class SecureHeadersTest extends TestCase
{
    /**
     * @var string
     */
    protected $configPath = __DIR__.'/../config/secure-headers.php';

    public function test_send_headers()
    {
        (new SecureHeaders($this->config()))->send();

        $headers = xdebug_get_headers();

        $this->assertContains(
            'X-Content-Type-Options: nosniff',
            $headers
        );

        $this->assertContains(
            'Referrer-Policy: no-referrer',
            $headers
        );
    }

    public function test_disable_header()
    {
        $config = $this->config();

        $config['x-download-options'] = null;

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArrayHasKey('X-Frame-Options', $headers);

        $this->assertArrayNotHasKey('X-Download-Options', $headers);
    }

    public function test_load_from_file()
    {
        $headers = SecureHeaders::fromFile($this->configPath)->headers();

        $this->assertArrayHasKey('Permissions-Policy', $headers);

        $this->assertArrayHasKey('X-Frame-Options', $headers);
    }

    public function test_file_not_found()
    {
        $this->expectException(InvalidArgumentException::class);

        SecureHeaders::fromFile(__DIR__.'/not-found');
    }

    public function test_server_header()
    {
        $config = $this->config();

        $this->assertArrayNotHasKey(
            'Server',
            (new SecureHeaders($config))->headers()
        );

        $config['server'] = 'Example';

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArrayHasKey('Server', $headers);

        $this->assertSame('Example', $headers['Server']);
    }

    public function test_x_powered_by_header()
    {
        $config = $this->config();

        $this->assertArrayNotHasKey(
            'X-Powered-By',
            (new SecureHeaders($config))->headers()
        );

        $config['x-powered-by'] = 'Example';

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArrayHasKey('X-Powered-By', $headers);

        $this->assertSame('Example', $headers['X-Powered-By']);

        // ensure backward compatibility

        unset($config['x-powered-by']);

        $this->assertArrayNotHasKey(
            'X-Powered-By',
            (new SecureHeaders($config))->headers()
        );

        $config['x-power-by'] = 'Example';

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArrayHasKey('X-Powered-By', $headers);

        $this->assertSame('Example', $headers['X-Powered-By']);
    }

    public function test_content_security_policy()
    {
        $config = $this->config();

        $config['csp']['enable'] = true;

        $this->assertArrayNotHasKey(
            'Content-Security-Policy',
            (new SecureHeaders($config))->headers()
        );

        $config['csp']['default-src']['self'] = true;

        $this->assertArrayHasKey(
            'Content-Security-Policy',
            (new SecureHeaders($config))->headers()
        );

        $config['csp']['report-only'] = true;

        $this->assertArrayHasKey(
            'Content-Security-Policy-Report-Only',
            (new SecureHeaders($config))->headers()
        );

        $this->assertArrayNotHasKey(
            'Content-Security-Policy',
            (new SecureHeaders($config))->headers()
        );

        $config['csp']['enable'] = false;

        $this->assertArrayNotHasKey(
            'Content-Security-Policy',
            (new SecureHeaders($config))->headers()
        );
    }

    public function test_content_security_policy_nonce()
    {
        $nonce = SecureHeaders::nonce();

        $headers = (new SecureHeaders($this->config()))->headers();

        $this->assertArrayHasKey(
            'Content-Security-Policy',
            $headers
        );

        $this->assertSame(
            sprintf("script-src 'nonce-%s'", $nonce),
            $headers['Content-Security-Policy']
        );

        $nonce = csp_nonce('style');

        $headers = (new SecureHeaders($this->config()))->headers();

        $this->assertArrayHasKey(
            'Content-Security-Policy',
            $headers
        );

        $this->assertSame(
            sprintf("style-src 'nonce-%s'", $nonce),
            $headers['Content-Security-Policy']
        );
    }

    public function test_content_security_policy_remove_nonce()
    {
        SecureHeaders::nonce('script');
        SecureHeaders::nonce('style');

        SecureHeaders::removeNonce();

        $headers = (new SecureHeaders($this->config()))->headers();

        $this->assertArrayNotHasKey(
            'Content-Security-Policy',
            $headers
        );

        $nonce = SecureHeaders::nonce('script');
        SecureHeaders::nonce('style');

        SecureHeaders::removeNonce('style');

        $headers = (new SecureHeaders($this->config()))->headers();

        $this->assertSame(
            sprintf("script-src 'nonce-%s'", $nonce),
            $headers['Content-Security-Policy']
        );

        $nonce1 = SecureHeaders::nonce('script');
        $nonce2 = SecureHeaders::nonce('script');

        SecureHeaders::removeNonce('script', $nonce1);

        $headers = (new SecureHeaders($this->config()))->headers();

        $this->assertSame(
            sprintf("script-src 'nonce-%s'", $nonce2),
            $headers['Content-Security-Policy']
        );
    }

    public function test_content_security_policy_nonce_will_be_cleared_after_header_sent()
    {
        $times = 10;

        while ($times--) {
            $nonce = SecureHeaders::nonce();

            $headers = (new SecureHeaders($this->config()))->headers();

            $this->assertSame(
                sprintf("script-src 'nonce-%s'", $nonce),
                $headers['Content-Security-Policy']
            );
        }
    }

    public function test_permissions_policy()
    {
        $config = $this->config();

        $config['permissions-policy']['enable'] = true;

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArrayHasKey('Permissions-Policy', $headers);

        $policy = $headers['Permissions-Policy'];

        $this->assertSame(
            'accelerometer=(self), ambient-light-sensor=(self), attribution-reporting=*, autoplay=(self), bluetooth=(self), browsing-topics=*, camera=(self), compute-pressure=(self), cross-origin-isolated=(self), display-capture=(self), document-domain=*, encrypted-media=(self), fullscreen=(self), gamepad=(self), geolocation=(self), gyroscope=(self), hid=(self), identity-credentials-get=(self), idle-detection=(self), local-fonts=(self), magnetometer=(self), microphone=(self), midi=(self), otp-credentials=(self), payment=(self), picture-in-picture=*, publickey-credentials-create=(self), publickey-credentials-get=(self), screen-wake-lock=(self), serial=(self), speaker-selection=(self), storage-access=*, usb=(self), web-share=(self), window-management=(self), xr-spatial-tracking=(self)',
            $policy
        );

        $config['permissions-policy']['enable'] = false;

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArrayNotHasKey('Permissions-Policy', $headers);
    }

    public function test_strict_transport_security()
    {
        $config = $this->config();

        $config['hsts']['enable'] = true;

        $this->assertArrayHasKey(
            'Strict-Transport-Security',
            (new SecureHeaders($config))->headers()
        );

        $config['hsts']['enable'] = false;

        $this->assertArrayNotHasKey(
            'Strict-Transport-Security',
            (new SecureHeaders($config))->headers()
        );
    }

    public function test_expect_certificate_transparency()
    {
        $config = $this->config();

        $config['expect-ct']['enable'] = true;

        $this->assertArrayHasKey(
            'Expect-CT',
            (new SecureHeaders($config))->headers()
        );

        $config['expect-ct']['enable'] = false;

        $this->assertArrayNotHasKey(
            'Expect-CT',
            (new SecureHeaders($config))->headers()
        );
    }

    public function test_clear_site_data()
    {
        $config = $this->config();

        $config['clear-site-data']['enable'] = true;

        $this->assertArrayHasKey(
            'Clear-Site-Data',
            (new SecureHeaders($config))->headers()
        );

        $config['clear-site-data']['enable'] = false;

        $this->assertArrayNotHasKey(
            'Clear-Site-Data',
            (new SecureHeaders($config))->headers()
        );
    }

    public function test_cross_origin_policy()
    {
        $config = $this->config();

        $config['cross-origin-resource-policy'] = 'same-origin';

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArrayHasKey('Cross-Origin-Resource-Policy', $headers);

        $this->assertSame('same-origin', $headers['Cross-Origin-Resource-Policy']);

        // ensure backward compatibility

        unset($config['cross-origin-resource-policy']);

        $this->assertArrayNotHasKey(
            'Cross-Origin-Resource-Policy',
            (new SecureHeaders($config))->headers()
        );
    }

    public function test_reporting_endpoints()
    {
        $config = $this->config();

        $config['reporting'] = [
            'nel' => 'https://example.com/nel',
            'csp' => 'https://example.com/csp',
        ];

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArrayHasKey('Reporting-Endpoints', $headers);

        $this->assertSame('nel="https://example.com/nel", csp="https://example.com/csp"', $headers['Reporting-Endpoints']);

        // ensure backward compatibility

        unset($config['reporting']);

        $this->assertArrayNotHasKey(
            'Reporting-Endpoints',
            (new SecureHeaders($config))->headers()
        );
    }

    public function test_network_error_logging()
    {
        $config = $this->config();

        $config['nel']['enable'] = true;

        $this->assertArrayNotHasKey(
            'NEL',
            (new SecureHeaders($config))->headers()
        );

        $config['nel']['report-to'] = 'nel';

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArrayHasKey('NEL', $headers);

        $this->assertSame('{"report_to":"nel","max_age":86400,"include_subdomains":false,"success_fraction":0.0,"failure_fraction":1.0}', $headers['NEL']);

        $config['nel']['include-subdomains'] = true;

        $config['nel']['failure-fraction'] = 0.01;

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArrayHasKey('NEL', $headers);

        $this->assertSame('{"report_to":"nel","max_age":86400,"include_subdomains":true,"success_fraction":0.0,"failure_fraction":0.01}', $headers['NEL']);

        $config['nel']['enable'] = false;

        $this->assertArrayNotHasKey(
            'NEL',
            (new SecureHeaders($config))->headers()
        );

        // ensure backward compatibility

        unset($config['reporting-endpoints']);

        $this->assertArrayNotHasKey(
            'NEL',
            (new SecureHeaders($config))->headers()
        );
    }

    /**
     * Get secure-headers config.
     *
     * @return array<mixed>
     */
    protected function config(): array
    {
        return require $this->configPath;
    }
}
