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
     */

    'x-frame-options' => 'sameorigin',

    /*
     * X-Permitted-Cross-Domain-Policies
     *
     * Reference: https://www.adobe.com/devnet/adobe-media-server/articles/cross-domain-xml-for-streaming.html
     *
     * Available Value: 'all', 'none', 'master-only', 'by-content-type', 'by-ftp-filename'
     */

    'x-permitted-cross-domain-policies' => 'none',

    /*
     * X-Power-By
     *
     * Note: it will not add to response header if the value is empty string.
     */

    'x-power-by' => '',

    /*
     * X-XSS-Protection
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-XSS-Protection
     *
     * Available Value: '1', '0', '1; mode=block'
     */

    'x-xss-protection' => '1; mode=block',

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
     * Clear-Site-Data
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Clear-Site-Data
     */

    'clear-site-data' => [
        'enable' => false,

        'all' => false,

        'cache' => true,

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
     * Expect-CT
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Expect-CT
     */

    'expect-ct' => [
        'enable' => false,

        'max-age' => 2147483648,

        'enforce' => false,

        'report-uri' => null,
    ],

    /*
     * Feature Policy
     *
     * Reference: https://w3c.github.io/webappsec-feature-policy/
     */

    'feature-policy' => [
        'enable' => true,

        /*
         * Each directive details can be found on:
         *
         * https://github.com/w3c/webappsec-feature-policy/blob/master/features.md
         *
         * 'none', '*' and 'self allow' are mutually exclusive,
         * the priority is 'none' > '*' > 'self allow'.
         */

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/accelerometer
        'accelerometer' => [
            'self' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/ambient-light-sensor
        'ambient-light-sensor' => [
            'self' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/autoplay
        'autoplay' => [
            'self' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/battery
        'battery' => [
            'self' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/camera
        'camera' => [
            'self' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/display-capture
        'display-capture' => [
            'self' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/document-domain
        'document-domain' => [
            '*' => true,
        ],

        // document-write (draft: https://github.com/w3c/webappsec-feature-policy/blob/master/policies/document-write.md)

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/encrypted-media
        'encrypted-media' => [
            'self' => true,
        ],

        // https://wicg.github.io/page-lifecycle/#feature-policies
        'execution-while-not-rendered' => [
            '*' => true,
        ],

        // https://wicg.github.io/page-lifecycle/#feature-policies
        'execution-while-out-of-viewport' => [
            '*' => true,
        ],

        // focus-without-user-activation (draft: https://github.com/w3c/webappsec-feature-policy/blob/master/policies/focus-without-user-activation.md)

        // font-display-late-swap (draft: https://github.com/w3c/webappsec-feature-policy/blob/master/policies/font-display-late-swap.md)

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/fullscreen
        'fullscreen' => [
            'self' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/geolocation
        'geolocation' => [
            'self' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/gyroscope
        'gyroscope' => [
            'self' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/layout-animations
        'layout-animations' => [
            'self' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/legacy-image-formats
        'legacy-image-formats' => [
            'self' => true,
        ],

        // loading-frame-default-eager (draft: https://github.com/w3c/webappsec-feature-policy/blob/master/policies/loading-frame-default-eager.md)

        // loading-image-default-eager (draft: https://github.com/w3c/webappsec-feature-policy/blob/master/policies/loading-image-default-eager.md)

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/magnetometer
        'magnetometer' => [
            'self' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/microphone
        'microphone' => [
            'self' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/midi
        'midi' => [
            'self' => true,
        ],

        // https://drafts.csswg.org/css-nav-1/#policy-feature
        'navigation-override' => [
            'self' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/oversized-images
        'oversized-images' => [
            '*' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/payment
        'payment' => [
            'self' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/picture-in-picture
        'picture-in-picture' => [
            '*' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/publickey-credentials
        'publickey-credentials' => [
            'self' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/sync-xhr
        'sync-xhr' => [
            '*' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/unoptimized-images
        'unoptimized-images' => [
            'self' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/unsized-media
        'unsized-media' => [
            '*' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/usb
        'usb' => [
            'self' => true,
        ],

        // vertical-scroll (draft: https://github.com/w3c/webappsec-feature-policy/blob/master/policies/vertical_scroll.md)

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/wake-lock
        'wake-lock' => [
            'self' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/xr-spatial-tracking
        'xr-spatial-tracking' => [
            'self' => true,
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

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/report-uri
        // @todo
        'report-uri' => null,
        'report-to' => '',

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/block-all-mixed-content
        'block-all-mixed-content' => false,

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/upgrade-insecure-requests
        'upgrade-insecure-requests' => false,

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/base-uri
        // @todo
        'base-uri' => [],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/connect-src
        // @todo
        'connect-src' => [],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/default-src
        // @todo
        'default-src' => [],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/font-src
        // @todo
        'font-src' => [],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/form-action
        // @todo
        'form-action' => [],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/frame-ancestors
        // @todo
        'frame-ancestors' => [],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/frame-src
        // @todo
        'frame-src' => [],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/img-src
        // @todo
        'img-src' => [],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/manifest-src
        // @todo
        'manifest-src' => [],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/media-src
        // @todo
        'media-src' => [],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/navigate-to
        // @todo
        'navigate-to' => [],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/object-src
        // @todo
        'object-src' => [],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/plugin-types
        'plugin-types' => [
            // 'application/pdf',
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/prefetch-src
        // @todo
        'prefetch-src' => [],

        // https://w3c.github.io/webappsec-trusted-types/dist/spec/#integration-with-content-security-policy
        'require-trusted-types-for' => [
            'enable' => false,

            'script' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/sandbox
        'sandbox' => [
            'enable' => false,

            'allow-downloads-without-user-activation' => false,

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
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/script-src
        // @todo
        'script-src' => [
            'allow' => [
                // 'url',
            ],

            'hashes' => [
                // 'sha256' => [
                //     'hash-value',
                // ],
            ],

            'nonces' => [
                // 'base64-encoded',
            ],

            'schemes' => [
                // 'https:',
            ],

            'self' => false,

            'unsafe-inline' => false,

            'unsafe-eval' => false,

            'strict-dynamic' => false,

            'unsafe-hashed-attributes' => false,

            // https://www.chromestatus.com/feature/5792234276388864
            'report-sample' => true,

            'add-generated-nonce' => false,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/script-src-attr
        // @todo
        'script-src-attr' => [],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/script-src-elem
        // @todo
        'script-src-elem' => [],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/style-src
        // @todo
        'style-src' => [
            'self' => false,

            'unsafe-inline' => false,

            'report-sample' => true,

            'add-generated-nonce' => false,

            'allow' => [
                //
            ],

            'schemes' => [
                // 'https:',
            ],

            'nonces' => [
                //
            ],

            'hashes' => [
                // 'sha256' => [
                //     'base64-encoded hash value',
                // ],
            ],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/style-src-attr
        // @todo
        'style-src-attr' => [],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/style-src-elem
        // @todo
        'style-src-elem' => [],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/worker-src
        // @todo
        'worker-src' => [],
    ],
];
