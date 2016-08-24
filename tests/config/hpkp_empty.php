<?php

return [
    /*
     * X-Content-Type-Options
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Content-Type-Options
     */

    'x-content-type-options' => 'nosniff',

    /*
     * X-Frame-Options
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Frame-Options
     */

    'x-frame-options' => 'sameorigin',

    /*
     * X-XSS-Protection
     *
     * Reference: https://blogs.msdn.microsoft.com/ieinternals/2011/01/31/controlling-the-xss-filter/
     */

    'x-xss-protection' => '1; mode=block',

    /*
     * HTTP Strict Transport Security
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/Security/HTTP_strict_transport_security
     *
     * Please ensure your website had set up ssl/tls before enable hsts.
     */

    'hsts' => [
        'enable' => env('SECURITY_HEADER_HSTS_ENABLE', false),

        'max-age' => 15552000,

        'include-sub-domains' => false,
    ],

    /*
     * Public Key Pinning
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/Security/Public_Key_Pinning
     *
     * When hashes is empty, hpkp will be ignored.
     */

    'hpkp' => [
        'hashes' => [
            //
        ],

        'include-sub-domains' => false,

        'max-age' => 15552000,

        'report-only' => false,

        'report-uri' => null,
    ],

    /*
     * Content Security Policy
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/Security/CSP
     *
     * If custom-csp is not null, csp will be ignored.
     */

    'custom-csp' => env('SECURITY_HEADER_CUSTOM_CSP', null),

    'csp' => [
        'report-only' => false,

        'report-uri' => null,

        'upgrade-insecure-requests' => false,

        'base-uri' => [
            //
        ],

        'default-src' => [
            //
        ],

        'child-src' => [
            //
        ],

        'script-src' => [
            'allow' => [
                //
            ],

            'hashes' => [
                //
            ],

            'nonces' => [
                //
            ],

            'self' => false,

            'unsafe-inline' => false,

            'unsafe-eval' => false,
        ],

        'style-src' => [
            'allow' => [
                //
            ],

            'self' => false,

            'unsafe-inline' => false,
        ],

        'img-src' => [
            'allow' => [
                //
            ],

            'types' => [
                //
            ],

            'self' => false,

            'data' => false,
        ],

        /*
         * The following directives are all use 'allow' and 'self' flag.
         */

        'font-src' => [
            'allow' => [
                //
            ],

            'self' => false,
        ],

        'connect-src' => [
            //
        ],

        'form-action' => [
            //
        ],

        'frame-ancestors' => [
            //
        ],

        'media-src' => [
            //
        ],

        'object-src' => [
            //
        ],

        'plugin-types' => [
            'allow' => [
                //
            ],
        ],
    ],
];
