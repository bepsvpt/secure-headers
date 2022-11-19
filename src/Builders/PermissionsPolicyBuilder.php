<?php

namespace Bepsvpt\SecureHeaders\Builders;

final class PermissionsPolicyBuilder extends Builder
{
    /**
     * {@inheritDoc}
     */
    public function get(): string
    {
        $result = [];

        foreach ($this->config as $name => $config) {
            if ($name === 'enable') {
                continue;
            }

            if (empty($val = $this->directive($config))) {
                continue;
            }

            $result[] = sprintf('%s=%s', $name, $val);
        }

        return implode(', ', $result);
    }

    /**
     * Parse specific policy value.
     *
     * @param  array<mixed>  $config
     * @return string
     */
    protected function directive(array $config): string
    {
        if ($config['none'] ?? false) {
            return '()';
        } elseif ($config['*'] ?? false) {
            return '*';
        }

        $origins = $this->origins($config['origins'] ?? []);

        if ($config['self'] ?? false) {
            array_unshift($origins, 'self');
        }

        return sprintf('(%s)', implode(' ', $origins));
    }

    /**
     * Get valid origins.
     *
     * @param  array<string>  $origins
     * @return array<string>
     */
    protected function origins(array $origins): array
    {
        // prevent user leave spaces by mistake
        $trimmed = array_map('trim', $origins);

        // filter array using FILTER_VALIDATE_URL
        $filters = filter_var_array($trimmed, FILTER_VALIDATE_URL);

        // get valid value
        $passes = array_filter($filters);

        // ensure indexes are numerically
        $urls = array_values($passes);

        return array_map(function (string $url) {
            return sprintf('"%s"', $url);
        }, $urls);
    }
}
