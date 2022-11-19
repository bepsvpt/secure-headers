<?php

namespace Bepsvpt\SecureHeaders\Builders;

final class StrictTransportSecurityBuilder extends Builder
{
    /**
     * {@inheritDoc}
     */
    public function get(): string
    {
        $directives[] = $this->maxAge();

        if ($this->config['include-sub-domains'] ?? false) {
            $directives[] = 'includeSubDomains';
        }

        if ($this->config['preload'] ?? false) {
            $directives[] = 'preload';
        }

        return implode('; ', $directives);
    }

    /**
     * Get max-age directive.
     *
     * @return string
     */
    protected function maxAge(): string
    {
        $origin = $this->config['max-age'] ?? 31536000;

        // convert to int
        $age = intval($origin);

        // prevent negative value
        $val = max($age, 0);

        return sprintf('max-age=%d', $val);
    }
}
