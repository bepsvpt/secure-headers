<?php

namespace Bepsvpt\SecureHeaders\Tests\Builders;

use Bepsvpt\SecureHeaders\Builders\ClearSiteDataBuilder;
use Bepsvpt\SecureHeaders\Tests\TestCase;

final class ClearSiteDataBuilderTest extends TestCase
{
    public function test_clear_site_data()
    {
        $config = [];

        $this->assertSame(
            '',
            (new ClearSiteDataBuilder($config))->get()
        );

        $config = [
            'all' => true,
        ];

        $this->assertSame(
            '"*"',
            (new ClearSiteDataBuilder($config))->get()
        );

        $config = [
            'cache' => true,

            'storage' => true,
        ];

        $this->assertSame(
            '"cache", "storage"',
            (new ClearSiteDataBuilder($config))->get()
        );

        $config = [
            'clientHints' => true,

            'executionContexts' => true,
        ];

        $this->assertSame(
            '"clientHints", "executionContexts"',
            (new ClearSiteDataBuilder($config))->get()
        );
    }
}
