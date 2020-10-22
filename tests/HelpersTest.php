<?php declare(strict_types=1);

namespace Bepsvpt\SecureHeaders\Tests;

use Bepsvpt\SecureHeaders\SecureHeaders;

class HelpersTest extends TestCase
{
    /**
     * @var string
     */
    protected $configPath = __DIR__ . '/../config/secure-headers.php';
    
    public function testCspNonce()
    {
        $nonce = csp_nonce();

        $headers = (new SecureHeaders($this->config()))->headers();

        $this->assertArrayHasKey(
            'Content-Security-Policy',
            $headers
        );

        $this->assertSame(
            sprintf("script-src 'nonce-%s'", $nonce),
            $headers['Content-Security-Policy']
        );
    }

    /**
     * Get secure-headers config.
     *
     * @return array<mixed>
     */
    protected function config(): array
    {
        return require $this->configPath;
    }
}
