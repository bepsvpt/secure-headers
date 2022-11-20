<?php

namespace Bepsvpt\SecureHeaders;

use Bepsvpt\SecureHeaders\Builders\ClearSiteDataBuilder;
use Bepsvpt\SecureHeaders\Builders\ContentSecurityPolicyBuilder;
use Bepsvpt\SecureHeaders\Builders\ExpectCertificateTransparencyBuilder;
use Bepsvpt\SecureHeaders\Builders\PermissionsPolicyBuilder;
use Bepsvpt\SecureHeaders\Builders\StrictTransportSecurityBuilder;
use Exception;
use InvalidArgumentException;
use RuntimeException;

class SecureHeaders
{
    /**
     * @var array<mixed>
     */
    protected $config = [];

    /**
     * Determate header had sent or not.
     *
     * @var bool
     */
    protected $sent = false;

    /**
     * Nonces for `script-src` and `style-src`.
     *
     * @var array<array<string>>
     */
    protected static $nonces = [
        'script' => [],

        'style' => [],
    ];

    /**
     * Constructor.
     *
     * @param  array<mixed>  $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        if ($this->sent) {
            self::removeNonce();
        }
    }

    /**
     * Load data from file.
     *
     * @param  string  $file
     * @return SecureHeaders
     */
    public static function fromFile($file)
    {
        if (!is_file($file)) {
            throw new InvalidArgumentException(
                sprintf('%s does not exist.', $file)
            );
        }

        $config = require $file;

        return new self($config);
    }

    /**
     *  Send HTTP headers.
     *
     * @return void
     */
    public function send()
    {
        if (headers_sent($file, $line)) {
            throw new RuntimeException(
                sprintf('Headers already sent in %s on line %d.', $file, $line)
            );
        }

        foreach ($this->headers() as $key => $value) {
            header(sprintf('%s: %s', $key, $value), true);
        }

        $this->sent = true;
    }

    /**
     * Get HTTP headers.
     *
     * @return array<string>
     */
    public function headers(): array
    {
        $headers = array_merge(
            $this->csp(),
            $this->permissionsPolicy(),
            $this->hsts(),
            $this->expectCT(),
            $this->clearSiteData(),
            $this->miscellaneous()
        );

        $this->sent = true;

        return array_filter($headers);
    }

    /**
     * Get CSP header.
     *
     * @return array<string>
     */
    protected function csp(): array
    {
        $config = $this->config['csp'] ?? [];

        if (!($config['enable'] ?? false)) {
            return [];
        }

        $config['script-src']['nonces'] = self::$nonces['script'];

        $config['style-src']['nonces'] = self::$nonces['style'];

        $key = ($config['report-only'] ?? false)
            ? 'Content-Security-Policy-Report-Only'
            : 'Content-Security-Policy';

        $builder = new ContentSecurityPolicyBuilder($config);

        return [$key => $builder->get()];
    }

    /**
     * Get Permissions Policy header.
     *
     * @return array<string>
     */
    protected function permissionsPolicy(): array
    {
        $config = $this->config['permissions-policy'] ?? [];

        if (!($config['enable'] ?? false)) {
            return [];
        }

        $builder = new PermissionsPolicyBuilder($config);

        return ['Permissions-Policy' => $builder->get()];
    }

    /**
     * Get HSTS header.
     *
     * @return array<string>
     */
    protected function hsts(): array
    {
        $config = $this->config['hsts'] ?? [];

        if (!($config['enable'] ?? false)) {
            return [];
        }

        $builder = new StrictTransportSecurityBuilder($config);

        return ['Strict-Transport-Security' => $builder->get()];
    }

    /**
     * Generate Expect-CT header.
     *
     * @return array<string>
     */
    protected function expectCT(): array
    {
        $config = $this->config['expect-ct'] ?? [];

        if (!($config['enable'] ?? false)) {
            return [];
        }

        $builder = new ExpectCertificateTransparencyBuilder($config);

        return ['Expect-CT' => $builder->get()];
    }

    /**
     * Generate Clear-Site-Data header.
     *
     * @return array<string>
     */
    protected function clearSiteData(): array
    {
        $config = $this->config['clear-site-data'] ?? [];

        if (!($config['enable'] ?? false)) {
            return [];
        }

        $builder = new ClearSiteDataBuilder($config);

        return ['Clear-Site-Data' => $builder->get()];
    }

    /**
     * Get miscellaneous headers.
     *
     * @return array<string>
     */
    protected function miscellaneous(): array
    {
        return array_filter([
            'X-Content-Type-Options' => $this->config['x-content-type-options'],
            'X-Download-Options' => $this->config['x-download-options'],
            'X-Frame-Options' => $this->config['x-frame-options'],
            'X-Permitted-Cross-Domain-Policies' => $this->config['x-permitted-cross-domain-policies'],
            'X-Powered-By' => $this->config['x-powered-by'] ?? ($this->config['x-power-by'] ?? ''),
            'X-XSS-Protection' => $this->config['x-xss-protection'],
            'Referrer-Policy' => $this->config['referrer-policy'],
            'Server' => $this->config['server'],
            'Cross-Origin-Embedder-Policy' => $this->config['cross-origin-embedder-policy'] ?? '',
            'Cross-Origin-Opener-Policy' => $this->config['cross-origin-opener-policy'] ?? '',
            'Cross-Origin-Resource-Policy' => $this->config['cross-origin-resource-policy'] ?? '',
        ]);
    }

    /**
     * Generate random nonce value for current request.
     *
     * @param  string  $target
     * @return string
     *
     * @throws Exception
     */
    public static function nonce(string $target = 'script'): string
    {
        $nonce = base64_encode(bin2hex(random_bytes(8)));

        self::$nonces[$target][] = $nonce;

        return $nonce;
    }

    /**
     * Remove specific nonce value or flush all nonce for the given target.
     *
     * @param  string|null  $target
     * @param  string|null  $nonce
     * @return void
     */
    public static function removeNonce(string $target = null, string $nonce = null)
    {
        if ($target === null) {
            self::$nonces['script'] = self::$nonces['style'] = [];
        } elseif (isset(self::$nonces[$target])) {
            if ($nonce === null) {
                self::$nonces[$target] = [];
            } elseif (false !== ($idx = array_search($nonce, self::$nonces[$target]))) {
                unset(self::$nonces[$target][$idx]);
            }
        }
    }
}
