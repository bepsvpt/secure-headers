<?php

namespace Bepsvpt\SecureHeaders;

use InvalidArgumentException;
use RuntimeException;

class SecureHeaders
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var bool
     */
    protected $compiled = false;

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $this->preprocessConfig($config);
    }

    /**
     * Load data from file.
     *
     * @param string $file
     *
     * @return SecureHeaders
     */
    public static function fromFile($file)
    {
        if (! is_file($file)) {
            throw new InvalidArgumentException("{$file} does not exist.");
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
            throw new RuntimeException("Headers already sent in {$file} on line {$line}."); // @codeCoverageIgnore
        }

        foreach ($this->headers() as $key => $value) {
            header("{$key}: {$value}", true);
        }
    }

    /**
     * Get HTTP headers.
     *
     * @return array
     */
    public function headers(): array
    {
        if (! $this->compiled) {
            $this->compile();
        }

        return $this->headers;
    }

    /**
     * Compile HTTP headers.
     *
     * @return void
     */
    protected function compile()
    {
        $this->headers = array_merge(
            $this->csp(),
            $this->featurePolicy(),
            $this->hpkp(),
            $this->hsts(),
            $this->expectCT(),
            $this->clearSiteData(),
            $this->miscellaneous()
        );

        $this->compiled = true;
    }

    /**
     * Get CSP header.
     *
     * @return array
     */
    protected function csp(): array
    {
        if (! is_null($this->config['custom-csp'])) {
            if (empty($this->config['custom-csp'])) {
                return [];
            }

            return [
                'Content-Security-Policy' => $this->config['custom-csp'],
            ];
        }

        return Builder::getCSPHeader($this->config['csp']);
    }

    /**
     * Get Feature Policy header.
     *
     * @return array
     */
    protected function featurePolicy(): array
    {
        if (! ($this->config['feature-policy']['enable'] ?? false)) {
            return [];
        }

        return Builder::getFeaturePolicyHeader($this->config['feature-policy']);
    }

    /**
     * Get HPKP header.
     *
     * @return array
     */
    protected function hpkp(): array
    {
        if (empty($this->config['hpkp']['hashes'])) {
            return [];
        }

        return Builder::getHPKPHeader($this->config['hpkp']);
    }

    /**
     * Get HSTS header.
     *
     * @return array
     */
    protected function hsts(): array
    {
        if (! $this->config['hsts']['enable']) {
            return [];
        }

        $hsts = "max-age={$this->config['hsts']['max-age']};";

        if ($this->config['hsts']['include-sub-domains']) {
            $hsts .= ' includeSubDomains;';
        }

        $hsts .= ' preload';

        return [
            'Strict-Transport-Security' => $hsts,
        ];
    }

    /**
     * Generate Expect-CT header.
     *
     * @return array
     */
    protected function expectCT(): array
    {
        if (! ($this->config['expect-ct']['enable'] ?? false)) {
            return [];
        }

        $ct = "max-age={$this->config['expect-ct']['max-age']}";

        if ($this->config['expect-ct']['enforce']) {
            $ct .= ', enforce';
        }

        if (! empty($this->config['expect-ct']['report-uri'])) {
            $ct .= sprintf(', report-uri="%s"', $this->config['expect-ct']['report-uri']);
        }

        return [
            'Expect-CT' => $ct,
        ];
    }

    /**
     * Generate Clear-Site-Data header.
     *
     * @return array
     */
    protected function clearSiteData(): array
    {
        if (! ($this->config['clear-site-data']['enable'] ?? false)) {
            return [];
        }

        if ($this->config['clear-site-data']['all']) {
            $csd = '"*"';
        } else {
            // simulate array_only, filter disabled and get keys
            $flags = array_keys(array_filter(array_intersect_key(
                $this->config['clear-site-data'],
                array_flip(['cache', 'cookies', 'storage', 'executionContexts'])
            )));

            if (empty($flags)) {
                return [];
            }

            $csd = sprintf('"%s"', implode('", "', $flags));
        }

        return [
            'Clear-Site-Data' => $csd,
        ];
    }

    /**
     * Get miscellaneous headers.
     *
     * @return array
     */
    protected function miscellaneous(): array
    {
        return array_filter([
            'X-Content-Type-Options' => $this->config['x-content-type-options'],
            'X-Download-Options' => $this->config['x-download-options'],
            'X-Frame-Options' => $this->config['x-frame-options'],
            'X-Permitted-Cross-Domain-Policies' => $this->config['x-permitted-cross-domain-policies'],
            'X-XSS-Protection' => $this->config['x-xss-protection'],
            'Referrer-Policy' => $this->config['referrer-policy'],
            'Server' => $this->config['server'] ?? '',
        ]);
    }

    /**
     * Preprocess config data.
     *
     * @param array $config
     *
     * @return array
     */
    protected function preprocessConfig(array $config): array
    {
        return $this->addGeneratedNonce($config);
    }

    /**
     * Add generated nonce value to script-src and style-src.
     *
     * @param array $config
     *
     * @return array
     */
    protected function addGeneratedNonce(array $config): array
    {
        if (($config['csp']['script-src']['add-generated-nonce'] ?? false) === true) {
            $config['csp']['script-src']['nonces'][] = self::nonce();
        }

        if (($config['csp']['style-src']['add-generated-nonce'] ?? false) === true) {
            $config['csp']['style-src']['nonces'][] = self::nonce();
        }

        return $config;
    }

    /**
     * Generate random nonce value for current request.
     *
     * @return string
     */
    public static function nonce(): string
    {
        static $nonce;

        return $nonce ?: $nonce = bin2hex(random_bytes(16));
    }
}
