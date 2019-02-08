<?php

namespace Bepsvpt\Tests\SecureHeaders;

use Bepsvpt\SecureHeaders\Builder;
use PHPUnit\Framework\TestCase;

class BuilderTest extends TestCase
{
    protected $hpkpConfig = [
        'hashes' => [
            'YLh1dUR9y6Kja30RrAn7JKnbQG/uEtLMkBgFF2Fuihg=',
            'x2NDL8tAZrl1FEjBBQX8ea2iSH/lzfNAjlDxOX8BePR=',
        ],

        'include-sub-domains' => false,

        'max-age' => 15552000,

        'report-only' => false,

        'report-uri' => null,
    ];

    protected $fpConfig = [
        'enable' => true,

        'camera' => [
            'none' => true,

            '*' => true,

            'self' => true,

            'allow' => [
                // 'url',
            ],
        ],

        'fullscreen' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'allow' => [
                'https://example.com',
                'https://www.example.org',
            ],
        ],

        'geolocation' => [
            'none' => false,

            '*' => true,

            'self' => true,

            'allow' => [
                // 'url',
            ],
        ],

        'microphone' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'allow' => [
                // 'url',
            ],
        ],

        'midi' => [
            'none' => false,

            '*' => false,

            'self' => false,

            'allow' => [
                'https://example.com',
            ],
        ],

        'not-exists' => [
            'self' => true,
        ],
    ];

    public function test_hpkp()
    {
        $header = Builder::getHPKPHeader($this->hpkpConfig);

        $this->assertSame(
            'pin-sha256="YLh1dUR9y6Kja30RrAn7JKnbQG/uEtLMkBgFF2Fuihg="; pin-sha256="x2NDL8tAZrl1FEjBBQX8ea2iSH/lzfNAjlDxOX8BePR="; max-age=15552000',
            $header['Public-Key-Pins']
        );
    }

    public function test_hpkp_others_fields()
    {
        $this->hpkpConfig['include-sub-domains'] = true;

        $this->hpkpConfig['max-age'] = 65535;

        $this->hpkpConfig['report-uri'] = 'https://www.example.com/hpkp-report';

        $header = Builder::getHPKPHeader($this->hpkpConfig);

        $this->assertSame(
            'pin-sha256="YLh1dUR9y6Kja30RrAn7JKnbQG/uEtLMkBgFF2Fuihg="; pin-sha256="x2NDL8tAZrl1FEjBBQX8ea2iSH/lzfNAjlDxOX8BePR="; max-age=65535; includeSubDomains; report-uri="https://www.example.com/hpkp-report"',
            $header['Public-Key-Pins']
        );
    }

    public function test_hpkp_report_only()
    {
        $this->hpkpConfig['report-only'] = true;

        $header = Builder::getHPKPHeader($this->hpkpConfig);

        $this->assertArrayNotHasKey('Public-Key-Pins', $header);

        $this->assertArrayHasKey('Public-Key-Pins-Report-Only', $header);

        $this->assertSame(
            'pin-sha256="YLh1dUR9y6Kja30RrAn7JKnbQG/uEtLMkBgFF2Fuihg="; pin-sha256="x2NDL8tAZrl1FEjBBQX8ea2iSH/lzfNAjlDxOX8BePR="; max-age=15552000',
            $header['Public-Key-Pins-Report-Only']
        );
    }

    public function test_feature_policy()
    {
        $header = Builder::getFeaturePolicyHeader($this->fpConfig);

        $this->assertSame(
            "camera 'none'; fullscreen 'self' https://example.com https://www.example.org; geolocation *; microphone 'self'; midi https://example.com",
            $header['Feature-Policy']
        );
    }

    public function test_feature_policy_without_directive()
    {
        $this->fpConfig = ['enable' => true];

        $header = Builder::getFeaturePolicyHeader($this->fpConfig);

        $this->assertArrayNotHasKey('Feature-Policy', $header);
    }

    public function test_feature_policy_with_empty_directive()
    {
        $this->fpConfig = ['enable' => true, 'camera' => [], 'fullscreen' => []];

        $header = Builder::getFeaturePolicyHeader($this->fpConfig);

        $this->assertArrayNotHasKey('Feature-Policy', $header);
    }

    public function test_csp()
    {
        $data = json_decode(file_get_contents(__DIR__.'/vectors/basic-csp.json'), true);

        $header = Builder::getCSPHeader($data);

        $this->assertEquals(
            trim(file_get_contents(__DIR__.'/vectors/basic-csp.out')),
            $header['Content-Security-Policy']
        );
    }

    public function test_invalid_hash_and_nonce()
    {
        $data = [
            'script-src' => [
                'hashes' => [
                    'sha128' => ['Y3NwLWJ1aWxkZXI='],
                    'sha256' => ['Y3NwLWJ-1aWxkZXI='],
                ],

                'nonces' => [
                    'Y3NwLWJ-1aWxkZXI=',
                    'Y3NwLWJ1aWxkZXI=',
                ],
            ],
        ];

        $header = Builder::getCSPHeader($data)['Content-Security-Policy'];

        $this->assertNotContains('sha128-Y3NwLWJ1aWxkZXI=', $header);
        $this->assertNotContains('sha256-Y3NwLWJ-1aWxkZXI=', $header);
        $this->assertNotContains('nonce-Y3NwLWJ-1aWxkZXI=', $header);
        $this->assertContains('nonce-Y3NwLWJ1aWxkZXI=', $header);
    }

    public function test_unsafe_eval_and_inline()
    {
        $data = [
            'script-src' => [
                'unsafe-eval' => true,
                'unsafe-inline' => true,
            ],
        ];

        $header = Builder::getCSPHeader($data)['Content-Security-Policy'];

        $this->assertContains("'unsafe-eval'", $header);
        $this->assertContains("'unsafe-inline'", $header);
    }

    public function test_special_directives()
    {
        $data = [
            'plugin-types' => [
                'application/x-shockwave-flash',
                'application/x-java-applet',
            ],

            'require-sri-for' => 'script style',

            'sandbox' => 'allow-presentation',
        ];

        $header = Builder::getCSPHeader($data)['Content-Security-Policy'];

        $this->assertContains('plugin-types application/x-shockwave-flash application/x-java-applet', $header);
        $this->assertContains('sandbox allow-presentation', $header);
        $this->assertContains('require-sri-for script style', $header);
    }

    public function test_report_only()
    {
        $data = [
            'report-only' => true,
            'block-all-mixed-content' => true,
            'upgrade-insecure-requests' => true,
        ];

        $header = Builder::getCSPHeader($data);

        $this->assertArrayHasKey('Content-Security-Policy-Report-Only', $header);
        $this->assertArrayNotHasKey('Content-Security-Policy', $header);

        $this->assertContains('block-all-mixed-content', $header['Content-Security-Policy-Report-Only']);
        $this->assertContains('upgrade-insecure-requests', $header['Content-Security-Policy-Report-Only']);
    }
}
