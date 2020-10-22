<?php

/**
 * This helper function makes it easier to use a nonce for inline scripts in views.
 * It matches the function used by similar package spatie/laravel-csp so libraries could check for it.
 *
 * Usage: <script nonce="{{ csp_nonce() }}">
 */
if (! function_exists('csp_nonce')) {
    function csp_nonce() : string
    {
        return Bepsvpt\SecureHeaders\SecureHeaders::nonce();
    }
}
