<?php

return [

    'x_content_type_options' => 'nosniff',

    'x_frame_options' => 'sameorigin',

    'x_xss_protection' => '1; mode=block',

    /*
     * Content Security Policy
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/Security/CSP
     */
    'csp' => [
        'rule' => '',

        /*
         * The URIs that should be excluded to add CSP header.
         */
        'except' => [
            //
        ],
    ],

    /*
     * Make sure you enable https first.
     */
    'force_https' => env('FORCE_HTTPS', false),

    /*
     * HTTP Strict Transport Security
     *
     * https://developer.mozilla.org/en-US/docs/Web/Security/HTTP_strict_transport_security
     *
     * Note: hsts will only add when the request is secure or config is set to force https
     */
    'hsts' => [
        'enable' => false,

        'max_age' => 15552000,

        'include_sub_domains' => false,
    ],

    /*
     * Public Key Pinning
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/Security/Public_Key_Pinning
     *
     * Note: hpkp will only add when the request is secure or config is set to force https
     */
    'hpkp' => [
        'enable' => false,

        'pins' => [
            //
        ],

        'max_age' => 300,

        'include_sub_domains' => false,
    ],

];
