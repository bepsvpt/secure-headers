<?php

namespace Bepsvpt\Tests\SecureHeaders;

use PHPUnit\Framework\TestCase as BaseTestCase;
use PHPUnit\Runner\Version;

abstract class TestCase extends BaseTestCase
{
    /**
     * Handle phpunit deprecation.
     *
     * @return void
     */
    protected function assertStringContainsWrapper()
    {
        if ($this->isPhpunitBelow8()) {
            $this->assertContains(...func_get_args());
        } else {
            $this->assertStringContainsString(...func_get_args());
        }
    }

    /**
     * Handle phpunit deprecation.
     *
     * @return void
     */
    protected function assertStringNotContainsWrapper()
    {
        if ($this->isPhpunitBelow8()) {
            $this->assertNotContains(...func_get_args());
        } else {
            $this->assertStringNotContainsString(...func_get_args());
        }
    }

    /**
     * Detect phpunit version is below 8.0 or not.
     *
     * @return bool
     */
    protected function isPhpunitBelow8()
    {
        return version_compare(Version::series(), '8.0', '<');
    }
}
