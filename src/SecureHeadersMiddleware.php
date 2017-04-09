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

        // when response is BinaryFileResponse, we should not add headers
        if ($response instanceof BinaryFileResponse) {
            return $response;
        }

        $headers = (new SecureHeaders(config('secure-headers', [])))->headers();

        foreach ($headers as $key => $value) {
            $response->header($key, $value);
        }

        return $response;
    }
}
