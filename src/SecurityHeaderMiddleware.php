<?php

namespace Bepsvpt\LaravelSecurityHeader;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SecurityHeaderMiddleware
{
    /**
     * Headers that should append to response.
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->headers = [
            'X-Content-Type-Options' => config('security-header.x_content_type_options'),
            'X-Frame-Options' => config('security-header.x_frame_options'),
            'X-XSS-Protection' => config('security-header.x_xss_protection'),
        ];

        // If debug is enable, turn off csp
        if (! config('app.debug') && ! empty($csp = config('security-header.csp'))) {
            $this->headers['Content-Security-Policy'] = $this->headers['X-Content-Security-Policy'] = $csp;
        }

        if (config('security-header.hsts.enable')) {
            $this->hsts();
        }

        if (config('security-header.hpkp.enable')) {
            $this->hpkp();
        }

        if (! $request->secure() && config('security-header.force_https')) {
            return redirect()->secure($request->getRequestUri(), 302, $this->headers);
        }

        $response = $next($request);

        $response->headers->add($this->headers);

        return $response;
    }

    /**
     *  Parsing hsts header.
     */
    protected function hsts()
    {
        list($maxAge, $includeSubDomains) = [
            config('security-header.hsts.max_age'),
            config('security-header.hsts.include_sub_domains'),
        ];

        $this->headers['Strict-Transport-Security'] = "max-age={$maxAge}; preload;";

        if ($includeSubDomains) {
            $this->headers['Strict-Transport-Security'] .= ' includeSubDomains;';
        }
    }

    /**
     *  Parsing hpkp header.
     */
    protected function hpkp()
    {
        list($maxAge, $includeSubDomains) = [
            config('security-header.hpkp.max_age'),
            config('security-header.hpkp.include_sub_domains'),
        ];

        $this->headers['Public-Key-Pins'] = "max-age={$maxAge};";

        if ($includeSubDomains) {
            $this->headers['Public-Key-Pins'] .= ' includeSubDomains;';
        }

        foreach (config('security-header.hpkp.pins') as $pin) {
            $this->headers['Public-Key-Pins'] .= " pin-sha256=\"{$pin}\";";
        }
    }
}