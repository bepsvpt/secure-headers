<?php

namespace Bepsvpt\SecureHeaders\Tests\Builders;

use Bepsvpt\SecureHeaders\Builders\FeaturePolicyBuilder;
use Bepsvpt\SecureHeaders\Tests\TestCase;

final class FeaturePolicyBuilderTest extends TestCase
{
    public function testPolicies()
    {
        $config = [
            'autoplay' => [
                'none' => true,
            ],

            'battery' => [
                '*' => true,
            ],

            'camera' => [
                'self' => true,

                'allow' => [
                    'http://example.com'
                ],
            ],

            'midi' => [
                'src' => true,
            ],

            'usb' => [
                'allow' => [
                    'https://example.com'
                ],
            ],
        ];

        $this->assertSame(
            "autoplay 'none'; battery *; camera 'self' http://example.com; midi 'src'; usb https://example.com",
            (new FeaturePolicyBuilder($config))->get()
        );
    }

    public function testPolicyKeyPriority()
    {
        $config = [
            'accelerometer' => [
                'none' => true,

                '*' => true,
            ],
        ];

        $this->assertSame(
            "accelerometer 'none'",
            (new FeaturePolicyBuilder($config))->get()
        );

        $config = [
            'accelerometer' => [
                '*' => true,

                'self' => true,
            ],
        ];

        $this->assertSame(
            "accelerometer *",
            (new FeaturePolicyBuilder($config))->get()
        );

        $config = [
            'accelerometer' => [
                'none' => false,

                '*' => false,

                'src' => true,
            ],
        ];

        $this->assertSame(
            "accelerometer 'src'",
            (new FeaturePolicyBuilder($config))->get()
        );
    }

    public function testPolicyMissingKeys()
    {
        $config = [
            'accelerometer' => [
                'apple' => true,

                'banana' => true,
            ],
        ];

        $this->assertSame(
            '',
            (new FeaturePolicyBuilder($config))->get()
        );
    }

    public function testInvalidPolicyName()
    {
        $config = [
            'apple' => [
                'none' => true,
            ],

            'accelerometer' => [
                'none' => true,
            ],

            'banana' => [
                'none' => true,
            ],
        ];

        $this->assertSame(
            "accelerometer 'none'",
            (new FeaturePolicyBuilder($config))->get()
        );
    }

    public function testInvalidPolicyAllowList()
    {
        $config = [
            'accelerometer' => [
                'allow' => [
                    'https://example.com',

                    'http://^_^.example.com',
                ],
            ],

            'autoplay' => [
                'self' => true,

                'allow' => [
                    'apple is sweet',

                    'banana is yellow',
                ],
            ],
        ];

        $this->assertSame(
            "accelerometer https://example.com; autoplay 'self'",
            (new FeaturePolicyBuilder($config))->get()
        );
    }
}
