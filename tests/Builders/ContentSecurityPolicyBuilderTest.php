<?php

namespace Bepsvpt\SecureHeaders\Tests\Builders;

use Bepsvpt\SecureHeaders\Builders\ContentSecurityPolicyBuilder;
use Bepsvpt\SecureHeaders\Tests\TestCase;

final class ContentSecurityPolicyBuilderTest extends TestCase
{
    public function testEmptyDirective()
    {
        $config = [
            'img-src' => [],

            'font-src' => [],

            'script-src' => [
                'none' => false,

                'self' => false,

                'unsafe-eval' => false,

                'unsafe-hashes' => false,

                'unsafe-inline' => false,

                'strict-dynamic' => false,

                'report-sample' => false,

                'allow' => [],

                'hashes' => [
                    'sha256' => [],
                ],

                'nonces' => [],

                'schemes' => [],
            ],
        ];

        $this->assertSame(
            '',
            (new ContentSecurityPolicyBuilder($config))->get()
        );
    }

    public function testSeldomUsedDirectives()
    {
        // plugin-types
        $config = [
            'plugin-types' => [
                'application/x-shockwave-flash',
                'application/x-java-applet',
            ],
        ];

        $this->assertSame(
            'plugin-types application/x-shockwave-flash application/x-java-applet',
            (new ContentSecurityPolicyBuilder($config))->get()
        );

        // sandbox
        $config = [
            'sandbox' => [
                'enable' => true,
            ],
        ];

        $this->assertSame(
            'sandbox',
            (new ContentSecurityPolicyBuilder($config))->get()
        );

        $config = [
            'sandbox' => [
                'enable' => true,

                'allow-modals' => true,

                'allow-popups' => true,
            ],
        ];

        $this->assertSame(
            'sandbox allow-modals allow-popups',
            (new ContentSecurityPolicyBuilder($config))->get()
        );

        // require-trusted-types-for
        $config = [
            'require-trusted-types-for' => [
                'script' => true,
            ],
        ];

        $this->assertSame(
            "require-trusted-types-for 'script'",
            (new ContentSecurityPolicyBuilder($config))->get()
        );

        // trusted-types
        $config = [
            'trusted-types' => [
                'enable' => true,
            ],
        ];

        $this->assertSame(
            'trusted-types',
            (new ContentSecurityPolicyBuilder($config))->get()
        );

        $config = [
            'trusted-types' => [
                'enable' => true,

                'policies' => [
                    'one',
                ],
            ],
        ];

        $this->assertSame(
            'trusted-types one',
            (new ContentSecurityPolicyBuilder($config))->get()
        );

        $config = [
            'trusted-types' => [
                'enable' => true,

                'default' => true,

                'policies' => [
                    'one',
                    'two',
                ],
            ],
        ];

        $this->assertSame(
            'trusted-types one two default',
            (new ContentSecurityPolicyBuilder($config))->get()
        );

        $config = [
            'trusted-types' => [
                'enable' => true,

                'allow-duplicates' => true,

                'policies' => [
                    'one',
                    'two',
                ],
            ],
        ];

        $this->assertSame(
            "trusted-types one two 'allow-duplicates'",
            (new ContentSecurityPolicyBuilder($config))->get()
        );

        // report-to
        $config = [
            'report-to' => 'csp-report',
        ];

        $this->assertSame(
            'report-to csp-report',
            (new ContentSecurityPolicyBuilder($config))->get()
        );
    }

    public function testSchemeAutoAppendColon()
    {
        $config = [
            'img-src' => [
                'schemes' => [
                    'https',
                    'blob:',
                    'data',
                ],
            ],
        ];

        $this->assertSame(
            'img-src https: blob: data:',
            (new ContentSecurityPolicyBuilder($config))->get()
        );
    }

    public function testInvalidNonce()
    {
        $config = [
            'img-src' => [
                'nonces' => [
                    'hi-hour-are-you?',
                    'Mzk3Nzg4MTcwMiw2MjQ3MTQ3OTk=',
                ],
            ],
        ];

        $this->assertSame(
            "img-src 'nonce-Mzk3Nzg4MTcwMiw2MjQ3MTQ3OTk='",
            (new ContentSecurityPolicyBuilder($config))->get()
        );
    }

    public function testInvalidHash()
    {
        $config = [
            'font-src' => [
                'self' => true,

                'hashes' => [
                    'sha777' => [
                        'eAhF1Kdccp0BTXM6nMW7SYBdV0c3fZwzcC177TQ692g=',
                    ],

                    'sha256' => [
                        'apple-is-sweet',
                    ],
                ],
            ],

            'img-src' => [
                'self' => true,
            ],
        ];

        $this->assertSame(
            "font-src 'self'; img-src 'self'",
            (new ContentSecurityPolicyBuilder($config))->get()
        );
    }

    /*
     * https://signin.104.com.tw
     */
    public function testUsing104ContentSecurityPolicy()
    {
        $csp = "default-src api.rollbar.com 'self'; font-src 'self' fonts.gstatic.com heapanalytics.com data:; style-src 'self' 'unsafe-inline' tagmanager.google.com fonts.googleapis.com heapanalytics.com; script-src 'self' 'unsafe-inline' 'unsafe-eval' tagmanager.google.com www.googletagmanager.com www.google-analytics.com ssl.google-analytics.com cdn.heapanalytics.com heapanalytics.com www.google.com.tw certify-js.alexametrics.com cdnjs.cloudflare.com static.104.com.tw data:; img-src 'self' ssl.gstatic.com www.gstatic.com www.googletagmanager.com www.google-analytics.com heapanalytics.com certify.alexametrics.com tls-detect.support.104.com.tw www.google.com.tw stats.g.doubleclick.net www.google.com ac.clazzrooms.com ac.beagiver.com signin.104dc.com signin.104.com.tw graphicwb.104.com.tw static.104.com.tw data:; frame-src 'self' www.google.com; connect-src 'self' www.google-analytics.com heapanalytics.com";

        $config = [
            'connect-src' => [
                'self' => true,

                'allow' => [
                    'heapanalytics.com',
                    'www.google-analytics.com',
                ],
            ],

            'default-src' => [
                'self' => true,

                'allow' => [
                    'api.rollbar.com',
                ],
            ],

            'font-src' => [
                'self' => true,

                'schemes' => [
                    'data:',
                ],

                'allow' => [
                    'fonts.gstatic.com',
                    'heapanalytics.com',
                ],
            ],

            'frame-src' => [
                'self' => true,

                'allow' => [
                    'www.google.com',
                ],
            ],

            'img-src' => [
                'self' => true,

                'schemes' => [
                    'data:',
                ],

                'allow' => [
                    'ac.beagiver.com',
                    'ac.clazzrooms.com',
                    'certify.alexametrics.com',
                    'graphicwb.104.com.tw',
                    'heapanalytics.com',
                    'signin.104.com.tw',
                    'signin.104dc.com',
                    'ssl.gstatic.com',
                    'static.104.com.tw',
                    'stats.g.doubleclick.net',
                    'tls-detect.support.104.com.tw',
                    'www.google.com',
                    'www.google.com.tw',
                    'www.google-analytics.com',
                    'www.googletagmanager.com',
                    'www.gstatic.com',
                ],
            ],

            'script-src' => [
                'self' => true,

                'unsafe-inline' => true,

                'unsafe-eval' => true,

                'schemes' => [
                    'data:',
                ],

                'allow' => [
                    'cdn.heapanalytics.com',
                    'cdnjs.cloudflare.com',
                    'certify-js.alexametrics.com',
                    'heapanalytics.com',
                    'ssl.google-analytics.com',
                    'static.104.com.tw',
                    'tagmanager.google.com',
                    'www.google.com.tw',
                    'www.google-analytics.com',
                    'www.googletagmanager.com',
                ],
            ],

            'style-src' => [
                'self' => true,

                'unsafe-inline' => true,

                'allow' => [
                    'fonts.googleapis.com',
                    'heapanalytics.com',
                    'tagmanager.google.com',
                ],
            ],
        ];

        $excepted = $this->split($csp);

        $actual = $this->split((new ContentSecurityPolicyBuilder($config))->get());

        $this->assertSame($excepted, $actual);
    }

    /*
     * https://user.gamer.com.tw/login.php
     */
    public function testUsingGamerContentSecurityPolicy()
    {
        $csp = "frame-ancestors 'self' https://*.gamer.com.tw";

        $config = [
            'frame-ancestors' => [
                'self' => true,

                'allow' => [
                    'https://*.gamer.com.tw',
                ],
            ],
        ];

        $excepted = $this->split($csp);

        $actual = $this->split((new ContentSecurityPolicyBuilder($config))->get());

        $this->assertSame($excepted, $actual);
    }

    /*
     * https://secure2.store.apple.com/shop/sign_in
     */
    public function testUsingAppleContentSecurityPolicy()
    {
        $csp = "frame-ancestors 'self'";

        $config = [
            'frame-ancestors' => [
                'self' => true,
            ],
        ];

        $excepted = $this->split($csp);

        $actual = $this->split((new ContentSecurityPolicyBuilder($config))->get());

        $this->assertSame($excepted, $actual);
    }

    /*
     * https://dash.cloudflare.com/login
     */
    public function testUsingCloudflareContentSecurityPolicy()
    {
        $csp = "object-src 'none'; script-src 'nonce-Mzk3Nzg4MTcwMiw2MjQ3MTQ3OTk=' 'unsafe-eval' 'strict-dynamic' 'report-sample' https:; base-uri 'self'";

        $config = [
            'base-uri' => [
                'self' => true,
            ],

            'object-src' => [
                'none' => true,
            ],

            'script-src' => [
                'unsafe-eval' => true,

                'strict-dynamic' => true,

                'report-sample' => true,

                'nonces' => [
                    'Mzk3Nzg4MTcwMiw2MjQ3MTQ3OTk=',
                ],

                'schemes' => [
                    'https:',
                ],
            ],
        ];

        $excepted = $this->split($csp);

        $actual = $this->split((new ContentSecurityPolicyBuilder($config))->get());

        $this->assertSame($excepted, $actual);
    }

    /*
     * https://www.facebook.com
     */
    public function testUsingFacebookContentSecurityPolicy()
    {
        $csp = "default-src * data: blob: 'self'; script-src *.facebook.com *.fbcdn.net *.facebook.net *.google-analytics.com *.virtualearth.net *.google.com 127.0.0.1:* *.spotilocal.com:* 'unsafe-inline' 'unsafe-eval' blob: data: 'self'; style-src data: blob: 'unsafe-inline' *; connect-src *.facebook.com facebook.com *.fbcdn.net *.facebook.net *.spotilocal.com:* wss://*.facebook.com:* https://fb.scanandcleanlocal.com:* attachment.fbsbx.com ws://localhost:* blob: *.cdninstagram.com 'self'; block-all-mixed-content; upgrade-insecure-requests";

        $config = [
            'block-all-mixed-content' => true,

            'upgrade-insecure-requests' => true,

            'connect-src' => [
                'self' => true,

                'schemes' => [
                    'blob:',
                ],

                'allow' => [
                    '*.cdninstagram.com',
                    '*.facebook.com',
                    '*.facebook.net',
                    '*.fbcdn.net',
                    '*.spotilocal.com:*',
                    'attachment.fbsbx.com',
                    'facebook.com',
                    'https://fb.scanandcleanlocal.com:*',
                    'ws://localhost:*',
                    'wss://*.facebook.com:*',
                ],
            ],

            'default-src' => [
                'self' => true,

                'schemes' => [
                    'data:',
                    'blob:',
                ],

                'allow' => [
                    '*',
                ],
            ],

            'script-src' => [
                'self' => true,

                'unsafe-inline' => true,

                'unsafe-eval' => true,

                'schemes' => [
                    'blob:',
                    'data:',
                ],

                'allow' => [
                    '*.fbcdn.net',
                    '*.facebook.com',
                    '*.facebook.net',
                    '*.google.com',
                    '*.google-analytics.com',
                    '*.spotilocal.com:*',
                    '*.virtualearth.net',
                    '127.0.0.1:*',
                ],
            ],

            'style-src' => [
                'unsafe-inline' => true,

                'schemes' => [
                    'data:',
                    'blob:',
                ],

                'allow' => [
                    '*',
                ],
            ],
        ];

        $excepted = $this->split($csp);

        $actual = $this->split((new ContentSecurityPolicyBuilder($config))->get());

        $this->assertSame($excepted, $actual);
    }

    /*
     * https://github.com/
     */
    public function testUsingGitHubContentSecurityPolicy()
    {
        $csp = "default-src 'none'; base-uri 'self'; block-all-mixed-content; connect-src 'self' uploads.github.com www.githubstatus.com collector.githubapp.com api.github.com www.google-analytics.com github-cloud.s3.amazonaws.com github-production-repository-file-5c1aeb.s3.amazonaws.com github-production-upload-manifest-file-7fdce7.s3.amazonaws.com github-production-user-asset-6210df.s3.amazonaws.com wss://live.github.com; font-src github.githubassets.com; form-action 'self' github.com gist.github.com; frame-ancestors 'none'; frame-src render.githubusercontent.com; img-src 'self' data: github.githubassets.com identicons.github.com collector.githubapp.com github-cloud.s3.amazonaws.com *.githubusercontent.com customer-stories-feed.github.com spotlights-feed.github.com; manifest-src 'self'; media-src 'none'; script-src github.githubassets.com; style-src 'unsafe-inline' github.githubassets.com";

        $config = [
            'block-all-mixed-content' => true,

            'base-uri' => [
                'self' => true,
            ],

            'connect-src' => [
                'self' => true,

                'allow' => [
                    'api.github.com',
                    'collector.githubapp.com',
                    'github-cloud.s3.amazonaws.com',
                    'github-production-repository-file-5c1aeb.s3.amazonaws.com',
                    'github-production-upload-manifest-file-7fdce7.s3.amazonaws.com',
                    'github-production-user-asset-6210df.s3.amazonaws.com',
                    'uploads.github.com',
                    'wss://live.github.com',
                    'www.githubstatus.com',
                    'www.google-analytics.com',
                ],
            ],

            'default-src' => [
                'none' => true,
            ],

            'font-src' => [
                'allow' => [
                    'github.githubassets.com',
                ],
            ],

            'form-action' => [
                'self' => true,

                'allow' => [
                    'gist.github.com',
                    'github.com',
                ],
            ],

            'frame-ancestors' => [
                'none' => true,
            ],

            'frame-src' => [
                'allow' => [
                    'render.githubusercontent.com',
                ],
            ],

            'img-src' => [
                'self' => true,

                'schemes' => [
                    'data:',
                ],

                'allow' => [
                    '*.githubusercontent.com',
                    'collector.githubapp.com',
                    'customer-stories-feed.github.com',
                    'github.githubassets.com',
                    'github-cloud.s3.amazonaws.com',
                    'identicons.github.com',
                    'spotlights-feed.github.com',
                ],
            ],

            'manifest-src' => [
                'self' => true,
            ],

            'media-src' => [
                'none' => true,
            ],

            'script-src' => [
                'allow' => [
                    'github.githubassets.com',
                ],
            ],

            'style-src' => [
                'unsafe-inline' => true,

                'allow' => [
                    'github.githubassets.com',
                ],
            ],
        ];

        $excepted = $this->split($csp);

        $actual = $this->split((new ContentSecurityPolicyBuilder($config))->get());

        $this->assertSame($excepted, $actual);
    }

    /*
     * https://accounts.google.com
     */
    public function testUsingGoogleContentSecurityPolicy()
    {
        $csp = "script-src 'nonce-oYvCFuvmnmawgMYMpObFBw' 'unsafe-inline' 'unsafe-eval'; object-src 'none'; base-uri 'self'; report-uri /cspreport";

        $config = [
            'report-uri' => [
                '/cspreport',
            ],

            'base-uri' => [
                'self' => true,
            ],

            'object-src' => [
                'none' => true,
            ],

            'script-src' => [
                'unsafe-inline' => true,

                'unsafe-eval' => true,

                'nonces' => [
                    'oYvCFuvmnmawgMYMpObFBw',
                ],
            ],
        ];

        $excepted = $this->split($csp);

        $actual = $this->split((new ContentSecurityPolicyBuilder($config))->get());

        $this->assertSame($excepted, $actual);
    }

    /*
     * https://www.paypal.com/tw/signin
     */
    public function testUsingPayPalContentSecurityPolicy()
    {
        $csp = "default-src 'self' https://*.paypal.com https://*.paypalobjects.com; frame-src 'self' https://*.brighttalk.com https://*.paypal.com https://*.paypalobjects.com https://www.youtube-nocookie.com https://www.xoom.com https://www.wootag.com; script-src 'nonce-JTDEJ1tJpkGVoRUcTBE9s6EbWk0sVDYtLrZ909/1KRzJcxGE' 'self' https://*.paypal.com https://*.paypalobjects.com https://assets-cdn.s-xoom.com 'unsafe-inline' 'unsafe-eval'; connect-src 'self' https://nominatim.openstreetmap.org https://*.paypal.com https://*.paypalobjects.com https://*.google-analytics.com https://*.salesforce.com https://*.force.com https://*.eloqua.com https://nexus.ensighten.com https://api.paypal-retaillocator.com https://*.brighttalk.com https://*.sperse.io https://*.dialogtech.com; style-src 'self' https://*.paypal.com https://*.paypalobjects.com https://assets-cdn.s-xoom.com 'unsafe-inline'; font-src 'self' https://*.paypal.com https://*.paypalobjects.com https://assets-cdn.s-xoom.com data:; img-src 'self' https: data:; form-action 'self' https://*.paypal.com https://*.salesforce.com https://*.eloqua.com https://secure.opinionlab.com; base-uri 'self' https://*.paypal.com; object-src 'none'; frame-ancestors 'self' https://*.paypal.com; block-all-mixed-content; report-uri https://www.paypal.com/csplog/api/log/csp";

        $config = [
            'report-uri' => [
                'https://www.paypal.com/csplog/api/log/csp',
            ],

            'block-all-mixed-content' => true,

            'base-uri' => [
                'self' => true,

                'allow' => [
                    'https://*.paypal.com',
                ],
            ],

            'connect-src' => [
                'self' => true,

                'allow' => [
                    'https://*.brighttalk.com',
                    'https://*.dialogtech.com',
                    'https://*.eloqua.com',
                    'https://*.force.com',
                    'https://*.google-analytics.com',
                    'https://*.paypal.com',
                    'https://*.paypalobjects.com',
                    'https://*.salesforce.com',
                    'https://*.sperse.io',
                    'https://api.paypal-retaillocator.com',
                    'https://nexus.ensighten.com',
                    'https://nominatim.openstreetmap.org',
                ],
            ],

            'default-src' => [
                'self' => true,

                'allow' => [
                    'https://*.paypal.com',
                    'https://*.paypalobjects.com',
                ],
            ],

            'font-src' => [
                'self' => true,

                'schemes' => [
                    'data:',
                ],

                'allow' => [
                    'https://*.paypal.com',
                    'https://*.paypalobjects.com',
                    'https://assets-cdn.s-xoom.com',
                ],
            ],

            'form-action' => [
                'self' => true,

                'allow' => [
                    'https://*.paypal.com',
                    'https://*.salesforce.com',
                    'https://*.eloqua.com',
                    'https://secure.opinionlab.com',
                ],
            ],

            'frame-ancestors' => [
                'self' => true,

                'allow' => [
                    'https://*.paypal.com',
                ],
            ],

            'frame-src' => [
                'self' => true,

                'allow' => [
                    'https://*.brighttalk.com',
                    'https://*.paypal.com',
                    'https://*.paypalobjects.com',
                    'https://www.wootag.com',
                    'https://www.xoom.com',
                    'https://www.youtube-nocookie.com',
                ],
            ],

            'img-src' => [
                'self' => true,

                'schemes' => [
                    'https:',
                    'data:',
                ],
            ],

            'object-src' => [
                'none' => true,
            ],

            'script-src' => [
                'self' => true,

                'unsafe-inline' => true,

                'unsafe-eval' => true,

                'nonces' => [
                    'JTDEJ1tJpkGVoRUcTBE9s6EbWk0sVDYtLrZ909/1KRzJcxGE',
                ],

                'allow' => [
                    'https://*.paypal.com',
                    'https://*.paypalobjects.com',
                    'https://assets-cdn.s-xoom.com',
                ],
            ],

            'style-src' => [
                'self' => true,

                'unsafe-inline' => true,

                'allow' => [
                    'https://*.paypal.com',
                    'https://*.paypalobjects.com',
                    'https://assets-cdn.s-xoom.com',
                ],
            ],
        ];

        $excepted = $this->split($csp);

        $actual = $this->split((new ContentSecurityPolicyBuilder($config))->get());

        $this->assertSame($excepted, $actual);
    }

    /*
     * https://beta.protonmail.com/
     */
    public function testUsingProtonMailContentSecurityPolicy()
    {
        $csp = "default-src 'self'; connect-src 'self' blob:; script-src 'self' blob: 'sha256-eAhF1Kdccp0BTXM6nMW7SYBdV0c3fZwzcC177TQ692g='; style-src 'self' 'unsafe-inline'; img-src http: https: data: blob: cid:; frame-src 'self' blob: https://secure.protonmail.com; object-src 'self' blob:; child-src 'self' data: blob:; report-uri https://reports.protonmail.ch/reports/csp; frame-ancestors 'none'";

        $config = [
            'report-uri' => [
                'https://reports.protonmail.ch/reports/csp',
            ],

            'child-src' => [
                'self' => true,

                'schemes' => [
                    'data:',
                    'blob:',
                ],
            ],

            'connect-src' => [
                'self' => true,

                'schemes' => [
                    'blob:',
                ],
            ],

            'default-src' => [
                'self' => true,
            ],

            'frame-ancestors' => [
                'none' => true,
            ],

            'frame-src' => [
                'self' => true,

                'schemes' => [
                    'blob:',
                ],

                'allow' => [
                    'https://secure.protonmail.com',
                ],
            ],

            'img-src' => [
                'schemes' => [
                    'http:',
                    'https:',
                    'data:',
                    'blob:',
                    'cid:',
                ],
            ],

            'object-src' => [
                'self' => true,

                'schemes' => [
                    'blob:',
                ],
            ],

            'script-src' => [
                'self' => true,

                'schemes' => [
                    'blob:',
                ],

                'hashes' => [
                    'sha256' => [
                        'eAhF1Kdccp0BTXM6nMW7SYBdV0c3fZwzcC177TQ692g=',
                    ],
                ],
            ],

            'style-src' => [
                'self' => true,

                'unsafe-inline' => true,
            ],
        ];

        $excepted = $this->split($csp);

        $actual = $this->split((new ContentSecurityPolicyBuilder($config))->get());

        $this->assertSame($excepted, $actual);
    }

    /*
     * https://www.reddit.com/login
     */
    public function testUsingRedditContentSecurityPolicy()
    {
        $csp = "frame-ancestors 'self' https://www.reddit.com https://new.reddit.com";

        $config = [
            'frame-ancestors' => [
                'self' => true,

                'allow' => [
                    'https://www.reddit.com',
                    'https://new.reddit.com',
                ],
            ],
        ];

        $excepted = $this->split($csp);

        $actual = $this->split((new ContentSecurityPolicyBuilder($config))->get());

        $this->assertSame($excepted, $actual);
    }

    /**
     * Split and sort csp to let it comparetable.
     *
     * @param  string  $csp
     * @return array<int, array<int, string>>
     */
    protected function split(string $csp): array
    {
        $directives = explode('; ', $csp);

        sort($directives);

        return array_map(function (string $directive) {
            $sources = explode(' ', $directive);

            sort($sources);

            return $sources;
        }, $directives);
    }
}
