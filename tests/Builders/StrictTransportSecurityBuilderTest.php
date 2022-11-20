<?php

namespace Bepsvpt\SecureHeaders\Tests\Builders;

use Bepsvpt\SecureHeaders\Builders\StrictTransportSecurityBuilder;
use Bepsvpt\SecureHeaders\Tests\TestCase;

final class StrictTransportSecurityBuilderTest extends TestCase
{
    public function testStrictTransportSecurity()
    {
        $config = [
            'max-age' => 1440,
        ];

        $this->assertSame(
            'max-age=1440',
            (new StrictTransportSecurityBuilder($config))->get()
        );

        $config = [
            'max-age' => 1440,

            'include-sub-domains' => true,
        ];

        $this->assertSame(
            'max-age=1440; includeSubDomains',
            (new StrictTransportSecurityBuilder($config))->get()
        );

        $config = [
            'max-age' => 1440,

            'preload' => true,
        ];

        $this->assertSame(
            'max-age=1440; preload',
            (new StrictTransportSecurityBuilder($config))->get()
        );

        $config = [
            'max-age' => 1440,

            'include-sub-domains' => true,

            'preload' => true,
        ];

        $this->assertSame(
            'max-age=1440; includeSubDomains; preload',
            (new StrictTransportSecurityBuilder($config))->get()
        );
    }

    public function testNegativeMaxAge()
    {
        $config = [
            'max-age' => -666,
        ];

        $this->assertSame(
            'max-age=0',
            (new StrictTransportSecurityBuilder($config))->get()
        );
    }
}
