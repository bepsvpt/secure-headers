<?php

namespace Bepsvpt\SecureHeaders;

use InvalidArgumentException;
use Bepsvpt\CSPBuilder\CSPBuilder;
use ParagonIE\HPKPBuilder\HPKPBuilder;
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
        $this->config = $config;
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
    public function headers()
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
            $this->hpkp(),
            $this->hsts(),
            $this->miscellaneous()
        );

        $this->compiled = true;
    }

    /**
     * Get CSP header.
     *
     * @return array
     */
    protected function csp()
    {
        if (! is_null($this->config['custom-csp'])) {
            if (empty($this->config['custom-csp'])) {
                return [];
            }

            return [
                'Content-Security-Policy' => $this->config['custom-csp'],
            ];
        }

        $csp = new CSPBuilder($this->config['csp']);

        if (! ($this->config['csp']['https-transform-on-https-connections'] ?? true)) {
            $csp = $csp->disableHttpTransformOnHttpsConnection();
        }

        return $csp->getHeaderArray();
    }

    /**
     * Get HPKP header.
     *
     * @return array
     */
    protected function hpkp()
    {
        if (empty($this->config['hpkp']['hashes'])) {
            return [];
        }

        $hpkp = (new HPKPBuilder($this->config['hpkp']))->getHeader();

        $headers = explode(':', $hpkp, 2);

        return [
            $headers[0] => trim($headers[1]),
        ];
    }

    /**
     * Get HSTS header.
     *
     * @return array
     */
    protected function hsts()
    {
        if (! $this->config['hsts']['enable']) {
            return [];
        }

        $hsts = "max-age={$this->config['hsts']['max-age']}; preload;";

        if ($this->config['hsts']['include-sub-domains']) {
            $hsts .= ' includeSubDomains;';
        }

        return [
            'Strict-Transport-Security' => $hsts,
        ];
    }

    /**
     * Get miscellaneous headers.
     *
     * @return array
     */
    protected function miscellaneous()
    {
        return array_filter([
            'X-Content-Type-Options' => $this->config['x-content-type-options'],
            'X-Download-Options' => $this->config['x-download-options'],
            'X-Frame-Options' => $this->config['x-frame-options'],
            'X-Permitted-Cross-Domain-Policies' => $this->config['x-permitted-cross-domain-policies'],
            'X-XSS-Protection' => $this->config['x-xss-protection'],
            'Referrer-Policy' => $this->config['referrer-policy'],
        ]);
    }
}
