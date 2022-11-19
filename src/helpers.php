<?php

use Bepsvpt\SecureHeaders\SecureHeaders;

if (!function_exists('csp_nonce')) {
    /**
     * This helper function makes it easier to generate
     * nonce for inline scripts and styles in views.
     *
     * @param  string  $target
     * @return string
     *
     * @throws Exception
     */
    function csp_nonce(string $target = 'script'): string
    {
        return SecureHeaders::nonce($target);
    }
}
