<?php

namespace Bepsvpt\SecureHeaders\Builders;

final class ExpectCertificateTransparencyBuilder extends Builder
{
    /**
     * Max age max value.
     *
     * @var int
     */
    protected $max = 2147483648;

    /**
     * {@inheritDoc}
     */
    public function get(): string
    {
        $directives[] = $this->maxAge();

        if ($this->config['enforce'] ?? false) {
            $directives[] = 'enforce';
        }

        if (!empty($this->config['report-uri'])) {
            $directives[] = $this->reportUri();
        }

        return implode(', ', array_filter($directives));
    }

    /**
     * Get max-age directive.
     *
     * @return string
     */
    protected function maxAge(): string
    {
        $origin = $this->config['max-age'] ?? $this->max;

        // convert to int
        $age = intval($origin);

        // prevent negative value
        $val = max($age, 0);

        return sprintf('max-age=%d', $val);
    }

    /**
     * Get report-uri directive.
     *
     * @return string
     */
    protected function reportUri(): string
    {
        $uri = filter_var($this->config['report-uri'], FILTER_VALIDATE_URL);

        if ($uri === false) {
            return '';
        }

        return sprintf('report-uri="%s"', $uri);
    }
}
