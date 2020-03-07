<?php

namespace Bepsvpt\SecureHeaders\Builders;

final class FeaturePolicyBuilder extends Builder
{
    /**
     * Feature policy whitelist directives.
     *
     * @var array<bool>
     */
    protected $whitelist = [
        'accelerometer' => true,
        'ambient-light-sensor' => true,
        'autoplay' => true,
        'battery' => true,
        'camera' => true,
        'display-capture' => true,
        'document-domain' => true,
        'document-write' => false,
        'encrypted-media' => true,
        'execution-while-not-rendered' => true,
        'execution-while-out-of-viewport' => true,
        'focus-without-user-activation' => false,
        'font-display-late-swap' => false,
        'fullscreen' => true,
        'geolocation' => true,
        'gyroscope' => true,
        'layout-animations' => true,
        'legacy-image-formats' => true,
        'loading-frame-default-eager' => false,
        'loading-image-default-eager' => false,
        'magnetometer' => true,
        'microphone' => true,
        'midi' => true,
        'navigation-override' => true,
        'oversized-images' => true,
        'payment' => true,
        'picture-in-picture' => true,
        'publickey-credentials' => true,
        'sync-xhr' => true,
        'unoptimized-images' => true,
        'unsized-media' => true,
        'usb' => true,
        'vertical-scroll' => false,
        'wake-lock' => true,
        'xr-spatial-tracking' => true,
    ];

    /**
     * @inheritDoc
     */
    public function get(): string
    {
        $result = [];

        foreach ($this->config as $name => $config) {
            if (!($this->whitelist[$name] ?? false)) {
                continue;
            }

            if (empty($val = $this->directive($config))) {
                continue;
            }

            $result[] = sprintf('%s %s', $name, $val);
        }

        return implode('; ', $result);
    }

    /**
     * Parse specific policy value.
     *
     * @param array $config
     *
     * @return string
     */
    protected function directive(array $config): string
    {
        if ($config['none'] ?? false) {
            return "'none'";
        } elseif ($config['*'] ?? false) {
            return '*';
        }

        $origins = array_merge(
            $this->origins($config),
            $this->urls($config['allow'] ?? [])
        );

        return trim(implode(' ', $origins));
    }

    /**
     * Get 'self' and 'src' origins.
     *
     * @param array<mixed> $config
     *
     * @return array<string>
     */
    protected function origins(array $config): array
    {
        $origins = ['self' => true, 'src' => true];

        // takes 'self' and 'src'
        $targets = array_intersect_key($config, $origins);

        // remains which value is `true`
        $needs = array_filter($targets);

        // convert `key` to value
        $values = array_keys($needs);

        // convert to RFC `allow-list-value` format
        return array_map(function (string $origin) {
            return sprintf("'%s'", $origin);
        }, $values);
    }

    /**
     * Get valid urls.
     *
     * @param array<string> $urls
     *
     * @return array<string>
     */
    protected function urls(array $urls): array
    {
        // prevent user leaving spaces by mistake
        $trimmed = array_map('trim', $urls);

        // filter array using FILTER_VALIDATE_URL
        $filters = filter_var_array($trimmed, FILTER_VALIDATE_URL);

        // get valid
        $passes = array_filter($filters);

        // ensure indexes are numerically
        return array_values($passes);
    }
}
