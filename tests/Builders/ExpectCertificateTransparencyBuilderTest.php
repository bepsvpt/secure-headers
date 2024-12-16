<?php

namespace Bepsvpt\SecureHeaders\Tests\Builders;

use Bepsvpt\SecureHeaders\Builders\ExpectCertificateTransparencyBuilder;
use Bepsvpt\SecureHeaders\Tests\TestCase;

final class ExpectCertificateTransparencyBuilderTest extends TestCase
{
    public function test_expect_certificate_transparency()
    {
        $config = [
            'max-age' => 1440,
        ];

        $this->assertSame(
            'max-age=1440',
            (new ExpectCertificateTransparencyBuilder($config))->get()
        );

        $config = [
            'max-age' => 1440,

            'enforce' => true,
        ];

        $this->assertSame(
            'max-age=1440, enforce',
            (new ExpectCertificateTransparencyBuilder($config))->get()
        );

        $config = [
            'max-age' => 1440,

            'report-uri' => 'https://ct.example.com/report',
        ];

        $this->assertSame(
            'max-age=1440, report-uri="https://ct.example.com/report"',
            (new ExpectCertificateTransparencyBuilder($config))->get()
        );

        $config = [
            'max-age' => 1440,

            'enforce' => true,

            'report-uri' => 'https://ct.example.com/report',
        ];

        $this->assertSame(
            'max-age=1440, enforce, report-uri="https://ct.example.com/report"',
            (new ExpectCertificateTransparencyBuilder($config))->get()
        );
    }

    public function test_negative_max_age()
    {
        $config = [
            'max-age' => -666,
        ];

        $this->assertSame(
            'max-age=0',
            (new ExpectCertificateTransparencyBuilder($config))->get()
        );
    }

    public function test_invalid_report_uri()
    {
        $config = [
            'max-age' => 86400,

            'report-uri' => 'yo~',
        ];

        $this->assertSame(
            'max-age=86400',
            (new ExpectCertificateTransparencyBuilder($config))->get()
        );
    }
}
