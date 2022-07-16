<?php

namespace Behnam\SecureHeaders\Tests\Behnam;

use Behnam\SecureHeaders\Behnam\ExceptCTBuilder;
use Behnam\SecureHeaders\Tests\TestCase;

final class ExceptCTBuilderTest extends TestCase
{
    public function testExceptCT()
    {
        $config = [
            'max-age' => 1440,
        ];

        $this->assertSame(
            'max-age=1440',
            (new ExceptCTBuilder($config))->get()
        );

        $config = [
            'max-age' => 1440,

            'enforce' => true,
        ];

        $this->assertSame(
            'max-age=1440, enforce',
            (new ExceptCTBuilder($config))->get()
        );

        $config = [
            'max-age' => 1440,

            'report-uri' => 'https://ct.example.com/report',
        ];

        $this->assertSame(
            'max-age=1440, report-uri="https://ct.example.com/report"',
            (new ExceptCTBuilder($config))->get()
        );

        $config = [
            'max-age' => 1440,

            'enforce' => true,

            'report-uri' => 'https://ct.example.com/report',
        ];

        $this->assertSame(
            'max-age=1440, enforce, report-uri="https://ct.example.com/report"',
            (new ExceptCTBuilder($config))->get()
        );
    }

    public function testNegativeMaxAge()
    {
        $config = [
            'max-age' => -666,
        ];

        $this->assertSame(
            'max-age=0',
            (new ExceptCTBuilder($config))->get()
        );
    }

    public function testInvalidReportUri()
    {
        $config = [
            'max-age' => 86400,

            'report-uri' => 'yo~',
        ];

        $this->assertSame(
            'max-age=86400',
            (new ExceptCTBuilder($config))->get()
        );
    }
}
