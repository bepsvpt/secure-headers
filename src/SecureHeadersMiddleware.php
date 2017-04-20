<?php

namespace Bepsvpt\SecureHeaders;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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

        $headers = (new SecureHeaders(config('secure-headers', [])))->headers();

        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value, true);
        }

        return $response;
    }
}
