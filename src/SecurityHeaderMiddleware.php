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
     * Security Header Config
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

        $this->loadConfig();

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
     * Load config.
     *
     * @return void
     */
    protected function loadConfig()
    {
        $path = config_path('security-header.json');

        if (! file_exists($path)) {
            $path = __DIR__.'/../config/security-header.json';
        }

        $this->config = json_decode(file_get_contents($path), true);
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

        $hsts = "max-age={$this->config['hsts']['max_age']}; preload;";

        if ($this->config['hsts']['include_sub_domains']) {
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
        $hpkp = (new HPKPBuilder($this->config['hpkp']))->getHeader();

        if (empty($hpkp)) {
            return [];
        }

        $headers = explode(':', $hpkp, 2);

        return [
            $headers[0] => trim($headers[1])
        ];
    }

    /**
     * Get csp header.
     *
     * @return array
     */
    protected function csp()
    {
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
            'X-Content-Type-Options' => $this->config['x_content_type_options'],
            'X-Frame-Options' => $this->config['x_frame_options'],
            'X-XSS-Protection' => $this->config['x_xss_protection'],
        ];
    }
}