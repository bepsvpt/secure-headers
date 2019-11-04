<?php

namespace Bepsvpt\Tests\SecureHeaders;

use PHPUnit\Framework\TestCase as BaseTestCase;
use PHPUnit\Runner\Version;
use PHPUnit_Runner_Version;

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
        if (class_exists(PHPUnit_Runner_Version::class)) {
            $version = PHPUnit_Runner_Version::series();
        } else {
            $version = Version::series();
        }

        return version_compare($version, '8.0', '<');
    }
}
