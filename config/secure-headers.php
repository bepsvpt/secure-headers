<?php

return [

    /*
     * Server
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Server
     *
     * Note: when server is empty string, it will not add to response header
     */

    'server' => '',

    /*
     * X-Content-Type-Options
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Content-Type-Options
     *
     * Available Value: 'nosniff'
     */

    'x-content-type-options' => 'nosniff',

    /*
     * X-DNS-Prefetch-Control
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-DNS-Prefetch-Control
     *
     * Available Value: 'on', 'off'
     */

    'x-dns-prefetch-control' => '',

    /*
     * X-Download-Options
     *
     * Reference: https://msdn.microsoft.com/en-us/library/jj542450(v=vs.85).aspx
     *
     * Available Value: 'noopen'
     */

    'x-download-options' => 'noopen',

    /*
     * X-Frame-Options
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Frame-Options
     *
     * Available Value: 'deny', 'sameorigin', 'allow-from <uri>'
     *
     * Note: The X-Frame-Options is no longer recommended for use; please use Content-Security-Policy (CSP) instead.
     */

    'x-frame-options' => 'sameorigin',

    /*
     * X-Permitted-Cross-Domain-Policies
     *
     * Reference: https://www.adobe.com/devnet-docs/acrobatetk/tools/AppSec/xdomain.html
     *
     * Available Value: 'all', 'none', 'master-only', 'by-content-type', 'by-ftp-filename'
     */

    'x-permitted-cross-domain-policies' => 'none',

    /*
     * X-Powered-By
     *
     * Note: it will not add to response header if the value is empty string.
     *
     * Also, verify that expose_php is turned Off in php.ini.
     * Otherwise the header will still be included in the response.
     *
     * Reference: https://github.com/bepsvpt/secure-headers/issues/58#issuecomment-782332442
     */

    'x-powered-by' => '',

    /*
     * X-XSS-Protection
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-XSS-Protection
     *
     * Available Value: '1', '0', '1; mode=block'
     *
     * Note: The X-XSS-Protection is no longer recommended for use; please use Content-Security-Policy (CSP) instead.
     */

    'x-xss-protection' => '',

    /*
     * Referrer-Policy
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Referrer-Policy
     *
     * Available Value: 'no-referrer', 'no-referrer-when-downgrade', 'origin', 'origin-when-cross-origin',
     *                  'same-origin', 'strict-origin', 'strict-origin-when-cross-origin', 'unsafe-url'
     */

    'referrer-policy' => 'no-referrer',

    /*
     * Cross-Origin-Embedder-Policy
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cross-Origin-Embedder-Policy
     *
     * Available Value: 'unsafe-none', 'require-corp', 'credentialless'
     */

    'cross-origin-embedder-policy' => 'unsafe-none',

    /*
     * Cross-Origin-Opener-Policy
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cross-Origin-Opener-Policy
     *
     * Available Value: 'unsafe-none', 'same-origin-allow-popups', 'same-origin'
     */

    'cross-origin-opener-policy' => 'unsafe-none',

    /*
     * Cross-Origin-Resource-Policy
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cross-Origin-Resource-Policy
     *
     * Available Value: 'same-site', 'same-origin', 'cross-origin'
     */

    'cross-origin-resource-policy' => 'cross-origin',

    /*
     * Clear-Site-Data
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Clear-Site-Data
     */

    'clear-site-data' => [
        'enable' => false,

        'all' => false,

        'cache' => true,

        'clientHints' => true,

        'cookies' => true,

        'storage' => true,

        'executionContexts' => true,
    ],

    /*
     * HTTP Strict Transport Security
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/Security/HTTP_strict_transport_security
     *
     * Please ensure your website had set up ssl/tls before enable hsts.
     */

    'hsts' => [
        'enable' => false,

        'max-age' => 31536000,

        'include-sub-domains' => false,

        'preload' => false,
    ],

    /*
     * Reporting Endpoints
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Reporting-Endpoints
     *
     * The array key is the endpoint name, and the value is the URL.
     */

    'reporting' => [
        // 'csp' => 'https://example.com/csp-reports',
        // 'nel' => 'https://example.com/nel-reports',
    ],

    /*
     * Network Error Logging
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Network_Error_Logging
     */

    'nel' => [
        'enable' => false,

        // The name of reporting API, not the endpoint URL.
        // @see https://developer.mozilla.org/en-US/docs/Web/API/Reporting_API
        'report-to' => '',

        'max-age' => 86400,

        'include-subdomains' => false,

        'success-fraction' => 0.0,

        'failure-fraction' => 1.0,
    ],

    /*
     * Expect-CT
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Expect-CT
     *
     * Note: This feature is no longer recommended.
     */

    'expect-ct' => [
        'enable' => false,

        'max-age' => 2147483648,

        'enforce' => false,

        // report uri must be absolute-URI
        'report-uri' => null,
    ],

    /*
     * Permissions Policy
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy
     */

    'permissions-policy' => [
        'enable' => true,

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/accelerometer
        'accelerometer' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/ambient-light-sensor
        'ambient-light-sensor' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/attribution-reporting
        'attribution-reporting' => [
            'none' => false,

            '*' => true,

            'self' => false,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/autoplay
        'autoplay' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/bluetooth
        'bluetooth' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/browsing-topics
        'browsing-topics' => [
            'none' => false,

            '*' => true,

            'self' => false,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/camera
        'camera' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/compute-pressure
        'compute-pressure' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/compute-pressure
        'cross-origin-isolated' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/display-capture
        'display-capture' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/document-domain
        'document-domain' => [
            'none' => false,

            '*' => true,

            'self' => false,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/encrypted-media
        'encrypted-media' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/fullscreen
        'fullscreen' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/gamepad
        'gamepad' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/geolocation
        'geolocation' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/gyroscope
        'gyroscope' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/hid
        'hid' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/identity-credentials-get
        'identity-credentials-get' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/idle-detection
        'idle-detection' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/local-fonts
        'local-fonts' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/magnetometer
        'magnetometer' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/microphone
        'microphone' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/midi
        'midi' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/otp-credentials
        'otp-credentials' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/payment
        'payment' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/picture-in-picture
        'picture-in-picture' => [
            'none' => false,

            '*' => true,

            'self' => false,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/publickey-credentials-create
        'publickey-credentials-create' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/publickey-credentials-get
        'publickey-credentials-get' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/screen-wake-lock
        'screen-wake-lock' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/serial
        'serial' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/speaker-selection
        'speaker-selection' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/storage-access
        'storage-access' => [
            'none' => false,

            '*' => true,

            'self' => false,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/usb
        'usb' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/web-share
        'web-share' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/window-management
        'window-management' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/xr-spatial-tracking
        'xr-spatial-tracking' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],
    ],

    /*
     * Content Security Policy
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP
     */

    'csp' => [
        'enable' => true,

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy-Report-Only
        'report-only' => false,

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/report-to
        'report-to' => '',

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/report-uri
        'report-uri' => [
            // uri
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/block-all-mixed-content
        'block-all-mixed-content' => false,

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/upgrade-insecure-requests
        'upgrade-insecure-requests' => false,

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/base-uri
        'base-uri' => [
            //
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/child-src
        'child-src' => [
            //
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/connect-src
        'connect-src' => [
            //
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/default-src
        'default-src' => [
            //
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/fenced-frame-src
        'fenced-frame-src' => [
            //
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/font-src
        'font-src' => [
            //
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/form-action
        'form-action' => [
            //
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/frame-ancestors
        'frame-ancestors' => [
            //
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/frame-src
        'frame-src' => [
            //
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/img-src
        'img-src' => [
            //
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/manifest-src
        'manifest-src' => [
            //
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/media-src
        'media-src' => [
            //
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/object-src
        'object-src' => [
            //
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/prefetch-src
        'prefetch-src' => [
            //
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/require-trusted-types-for
        'require-trusted-types-for' => [
            'script' => false,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/sandbox
        'sandbox' => [
            'enable' => false,

            'allow-downloads' => false,

            'allow-forms' => false,

            'allow-modals' => false,

            'allow-orientation-lock' => false,

            'allow-pointer-lock' => false,

            'allow-popups' => false,

            'allow-popups-to-escape-sandbox' => false,

            'allow-presentation' => false,

            'allow-same-origin' => false,

            'allow-scripts' => false,

            'allow-storage-access-by-user-activation' => false,

            'allow-top-navigation' => false,

            'allow-top-navigation-by-user-activation' => false,

            'allow-top-navigation-to-custom-protocols' => false,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/script-src
        'script-src' => [
            'none' => false,

            'self' => false,

            'report-sample' => false,

            'allow' => [
                // 'url',
            ],

            'schemes' => [
                // 'data:',
                // 'https:',
            ],

            /* followings are only work for `script` and `style` related directives */

            'unsafe-inline' => false,

            'unsafe-eval' => false,

            // https://www.w3.org/TR/CSP3/#unsafe-hashes-usage
            'unsafe-hashes' => false,

            // Enable `strict-dynamic` will *ignore* `self`, `unsafe-inline`,
            // `allow` and `schemes`. You can find more information from:
            // https://www.w3.org/TR/CSP3/#strict-dynamic-usage
            'strict-dynamic' => false,

            'hashes' => [
                'sha256' => [
                    // 'sha256-hash-value-with-base64-encode',
                ],

                'sha384' => [
                    // 'sha384-hash-value-with-base64-encode',
                ],

                'sha512' => [
                    // 'sha512-hash-value-with-base64-encode',
                ],
            ],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/script-src-attr
        'script-src-attr' => [
            //
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/script-src-elem
        'script-src-elem' => [
            //
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/style-src
        'style-src' => [
            //
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/style-src-attr
        'style-src-attr' => [
            //
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/style-src-elem
        'style-src-elem' => [
            //
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/trusted-types
        'trusted-types' => [
            'enable' => false,

            'none' => false,

            'allow-duplicates' => false,

            'policies' => [
                //
            ],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/worker-src
        'worker-src' => [
            //
        ],
    ],
];
