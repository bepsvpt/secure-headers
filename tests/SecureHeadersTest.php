<?php

namespace Bepsvpt\SecureHeaders\Tests;

use Bepsvpt\SecureHeaders\SecureHeaders;
use InvalidArgumentException;

final class SecureHeadersTest extends TestCase
{
    /**
     * @var string
     */
    protected $configPath = __DIR__ . '/../config/secure-headers.php';

    public function testSendHeaders()
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

    public function testDisableHeader()
    {
        $config = $this->config();

        $config['x-download-options'] = null;

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArrayHasKey('X-Frame-Options', $headers);

        $this->assertArrayNotHasKey('X-Download-Options', $headers);
    }

    public function testLoadFromFile()
    {
        $headers = SecureHeaders::fromFile($this->configPath)->headers();

        $this->assertArrayHasKey('Permissions-Policy', $headers);

        $this->assertArrayHasKey('X-XSS-Protection', $headers);
    }

    public function testFileNotFound()
    {
        $this->expectException(InvalidArgumentException::class);

        SecureHeaders::fromFile(__DIR__ . '/not-found');
    }

    public function testServerHeader()
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

    public function testXPoweredByHeader()
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

        $this->assertArrayHasKey('X-Powered-By', $headers);

        $this->assertSame('Example', $headers['X-Powered-By']);
    }

    public function testContentSecurityPolicy()
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

    public function testContentSecurityPolicyNonce()
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

    public function testContentSecurityPolicyRemoveNonce()
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

    public function testContentSecurityPolicyNonceWillBeClearedAfterHeaderSent()
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

    public function testPermissionsPolicy()
    {
        $config = $this->config();

        $config['permissions-policy']['enable'] = true;

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArrayHasKey('Permissions-Policy', $headers);

        $policy = $headers['Permissions-Policy'];

        $this->assertSame(
            'accelerometer=(self), ambient-light-sensor=(self), autoplay=(self), battery=(self), camera=(self), cross-origin-isolated=(self), display-capture=(self), document-domain=*, encrypted-media=(self), execution-while-not-rendered=*, execution-while-out-of-viewport=*, fullscreen=(self), geolocation=(self), gyroscope=(self), magnetometer=(self), microphone=(self), midi=(self), navigation-override=(self), payment=(self), picture-in-picture=*, publickey-credentials-get=(self), screen-wake-lock=(self), sync-xhr=*, usb=(self), web-share=(self), xr-spatial-tracking=(self)',
            $policy
        );

        $config['permissions-policy']['enable'] = false;

        $headers = (new SecureHeaders($config))->headers();

        $this->assertArrayNotHasKey('Permissions-Policy', $headers);
    }

    public function testStrictTransportSecurity()
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

    public function testExpectCertificateTransparency()
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

    public function testClearSiteData()
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

    public function testCrossOriginPolicy()
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
