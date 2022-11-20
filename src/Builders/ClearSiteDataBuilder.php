<?php

namespace Bepsvpt\SecureHeaders\Builders;

final class ClearSiteDataBuilder extends Builder
{
    /**
     * Clear Site Data whitelist directives.
     *
     * @var array<string, bool>
     */
    protected $whitelist = [
        'cache' => true,
        'cookies' => true,
        'storage' => true,
        'executionContexts' => true,
    ];

    /**
     * {@inheritDoc}
     */
    public function get(): string
    {
        if ($this->config['all'] ?? false) {
            return '"*"';
        }

        $targets = array_intersect_key($this->config, $this->whitelist);

        $needs = array_filter($targets);

        $directives = array_map(function (string $directive) {
            return sprintf('"%s"', $directive);
        }, array_keys($needs));

        return implode(', ', $directives);
    }
}
