<?php

namespace Bepsvpt\LaravelSecurityHeader;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use ParagonIE\CSPBuilder\CSPBuilder;
use ParagonIE\HPKPBuilder\HPKPBuilder;

class SecurityHeaderMiddleware
{
    /**
     * Security Header Config.
     *
     * @var array
     */
    private $config;

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $this->config = config('security-header');

        $response->withHeaders(
            array_merge(
                $this->csp(),
                $this->hpkp(),
                $this->hsts(),
                $this->miscellaneous()
            )
        );

        return $response;
    }

    /**
     * Get hsts header.
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
     * Get hpkp header.
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
     * Get csp header.
     *
     * @return array
     */
    protected function csp()
    {
        if (! is_null($this->config['custom-csp'])) {
            return [
                'Content-Security-Policy' => $this->config['custom-csp'],
            ];
        }

        $csp = new CSPBuilder($this->config['csp']);

        return $csp->getHeaderArray(false);
    }

    /**
     * Get miscellaneous headers.
     *
     * @return array
     */
    protected function miscellaneous()
    {
        return [
            'X-Content-Type-Options' => $this->config['x-content-type-options'],
            'X-Download-Options' => $this->config['x-download-options'],
            'X-Frame-Options' => $this->config['x-frame-options'],
            'X-Permitted-Cross-Domain-Policies' => $this->config['x-permitted-cross-domain-policies'],
            'X-XSS-Protection' => $this->config['x-xss-protection'],
            'Referrer-Policy' => $this->config['referrer-policy'],
        ];
    }
}
