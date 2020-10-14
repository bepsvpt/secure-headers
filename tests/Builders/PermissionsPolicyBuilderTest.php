<?php

namespace Bepsvpt\SecureHeaders\Tests\Builders;

use Bepsvpt\SecureHeaders\Builders\PermissionsPolicyBuilder;
use Bepsvpt\SecureHeaders\Tests\TestCase;

final class PermissionsPolicyBuilderTest extends TestCase
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

                'origins' => [
                    'http://example.com',
                ],
            ],

            'usb' => [
                'origins' => [
                    'https://example.com',
                    'https://www.example.com',
                ],
            ],
        ];

        $this->assertSame(
            'autoplay=(), battery=*, camera=(self "http://example.com"), usb=("https://example.com" "https://www.example.com")',
            (new PermissionsPolicyBuilder($config))->get()
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
            'accelerometer=()',
            (new PermissionsPolicyBuilder($config))->get()
        );

        $config = [
            'accelerometer' => [
                '*' => true,

                'self' => true,
            ],
        ];

        $this->assertSame(
            'accelerometer=*',
            (new PermissionsPolicyBuilder($config))->get()
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
            'accelerometer=()',
            (new PermissionsPolicyBuilder($config))->get()
        );
    }

    public function testInvalidPolicyAllowList()
    {
        $config = [
            'accelerometer' => [
                'origins' => [
                    'https://example.com',

                    'http://^_^.example.com',
                ],
            ],

            'autoplay' => [
                'self' => true,

                'origins' => [
                    'apple is sweet',

                    'banana is yellow',
                ],
            ],
        ];

        $this->assertSame(
            'accelerometer=("https://example.com"), autoplay=(self)',
            (new PermissionsPolicyBuilder($config))->get()
        );
    }
}
