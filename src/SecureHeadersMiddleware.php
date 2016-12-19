<?php

namespace Bepsvpt\SecureHeaders;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SecureHeadersMiddleware
{
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

        $response->withHeaders(
            (new SecureHeaders(config('secure-headers', [])))->headers()
        );

        return $response;
    }
}
