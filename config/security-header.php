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
    'csp' => '',

    /*
     * Make sure you enable https first.
     */
    'force_https' => false,

    /*
     * HTTP Strict Transport Security
     *
     * https://developer.mozilla.org/en-US/docs/Web/Security/HTTP_strict_transport_security
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
