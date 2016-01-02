<?php

namespace Bepsvpt\LaravelSecurityHeader;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaderMiddleware
{
    /**
     * Security Header Config
     *
     * @var array
     */
    private $config;

    /**
     * Headers that should append to response.
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Create a new error binder instance.
     */
    public function __construct()
    {
        $this->config = config('security-header');
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->headers = [
            'X-Content-Type-Options' => $this->config['x_content_type_options'],
            'X-Frame-Options' => $this->config['x_frame_options'],
            'X-XSS-Protection' => $this->config['x_xss_protection'],
        ];

        $this->csp($request);

        /*
         * Only the following status will add hsts and hpkp to response header.
         *
         * 1. Request is already secure.
         * 2. Force https is enable.
         */
        if ($request->secure() || $this->config['force_https']) {
            $this->hsts();
            $this->hpkp();

            if (! $request->secure()) {
                return redirect()->secure($request->getRequestUri(), 302, $this->headers);
            }
        }

        $response = $next($request);

        $response->headers->add($this->headers);

        return $response;
    }

    /**
     * Add csp to response header.
     *
     * @param Request $request
     * @return void
     */
    protected function csp(Request $request)
    {
        if (empty($this->config['csp']['rule'])) {
            return;
        }

        foreach ($this->config['csp']['except'] as $except) {
            if ($request->is($except)) {
                return;
            }
        }

        $this->headers['Content-Security-Policy']
            = $this->headers['X-Content-Security-Policy']
            = $this->headers['X-WebKit-CSP']
            = $this->config['csp']['rule'];
    }

    /**
     *  Add hsts to response header.
     *
     * @return void
     */
    protected function hsts()
    {
        if (! $this->config['hsts']['enable']) {
            return;
        }

        $hsts = "max-age={$this->config['hsts']['max_age']}; preload;";

        if ($this->config['hsts']['include_sub_domains']) {
            $hsts .= ' includeSubDomains;';
        }

        $this->headers['Strict-Transport-Security'] = $hsts;
    }

    /**
     *  Add hpkp to response header.
     *
     * @return void
     */
    protected function hpkp()
    {
        if (! $this->config['hpkp']['enable']) {
            return;
        }

        $hpkp = "max-age={$this->config['hpkp']['max_age']};";

        if ($this->config['hpkp']['include_sub_domains']) {
            $hpkp .= ' includeSubDomains;';
        }

        foreach ($this->config['hpkp']['pins'] as $pin) {
            $hpkp .= " pin-sha256=\"{$pin}\";";
        }

        $this->headers['Public-Key-Pins'] = $hpkp;
    }
}