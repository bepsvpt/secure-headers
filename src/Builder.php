<?php

namespace Bepsvpt\SecureHeaders;

class Builder
{
    /**
     * Generate HPKP header.
     *
     * @param array $config
     *
     * @return array
     */
    public static function getHPKPHeader(array $config): array
    {
        $headers = [];

        foreach ($config['hashes'] as $hash) {
            $headers[] = sprintf('pin-sha256="%s"', $hash);
        }

        $headers[] = sprintf('max-age=%d', $config['max-age']);

        if ($config['include-sub-domains']) {
            $headers[] = 'includeSubDomains';
        }

        if (! empty($config['report-uri'])) {
            $headers[] = sprintf('report-uri="%s"', $config['report-uri']);
        }

        $key = $config['report-only']
            ? 'Public-Key-Pins-Report-Only'
            : 'Public-Key-Pins';

        return [$key => implode('; ', $headers)];
    }

    /**
     * Generate Feature Policy header.
     *
     * @param array $config
     *
     * @return array
     */
    public static function getFeaturePolicyHeader(array $config): array
    {
        $directives = [
            'accelerometer',
            'ambient-light-sensor',
            'autoplay',
            'camera',
            'encrypted-media',
            'fullscreen',
            'geolocation',
            'gyroscope',
            'magnetometer',
            'microphone',
            'midi',
            'payment',
            'picture-in-picture',
            'speaker',
            'sync-xhr',
            'usb',
            'var',
        ];

        foreach ($directives as $directive) {
            if (! isset($config[$directive]) || empty($config[$directive])) {
                continue;
            }

            $value = '';

            if ($config[$directive]['none']) {
                $value = "'none'";
            } elseif ($config[$directive]['*']) {
                $value = '*';
            } else {
                if ($config[$directive]['self']) {
                    $value = "'self'";
                }

                foreach ($config[$directive]['allow'] as $url) {
                    if (false !== ($url = filter_var($url, FILTER_SANITIZE_URL))) {
                        $value = sprintf('%s %s', $value, $url);
                    }
                }
            }

            if (strlen($value = trim($value)) > 0) {
                $headers[] = sprintf('%s %s', $directive, $value);
            }
        }

        if (! isset($headers)) {
            return [];
        }

        return ['Feature-Policy' => implode('; ', $headers)];
    }

    /**
     * Generate CSP header.
     *
     * @param array $config
     *
     * @return array
     */
    public static function getCSPHeader(array $config): array
    {
        $directives = [
            'default-src',
            'base-uri',
            'connect-src',
            'font-src',
            'form-action',
            'frame-ancestors',
            'frame-src',
            'img-src',
            'manifest-src',
            'media-src',
            'object-src',
            'plugin-types',
            'require-sri-for',
            'sandbox',
            'script-src',
            'style-src',
            'worker-src',
        ];

        $headers = [];

        foreach ($directives as $directive) {
            if (isset($config[$directive])) {
                $headers[] = self::compileDirective($directive, $config[$directive]);
            }
        }

        if (! empty($config['block-all-mixed-content'])) {
            $headers[] = 'block-all-mixed-content';
        }

        if (! empty($config['upgrade-insecure-requests'])) {
            $headers[] = 'upgrade-insecure-requests';
        }

        if (! empty($config['report-uri'])) {
            $headers[] = sprintf('report-uri %s', $config['report-uri']);
        }

        $key = ! empty($config['report-only'])
            ? 'Content-Security-Policy-Report-Only'
            : 'Content-Security-Policy';

        return [$key => implode('; ', array_filter($headers, 'strlen'))];
    }

    /**
     * Compile a subgroup into a policy string.
     *
     * @param string $directive
     * @param mixed $policies
     *
     * @return string
     */
    protected static function compileDirective(string $directive, $policies): string
    {
        // handle special directive first
        switch ($directive) {
            // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/plugin-types
            case 'plugin-types':
                return empty($policies) ? '' : sprintf('%s %s', $directive, implode(' ', $policies));

            // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/require-sri-for
            // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/sandbox
            case 'require-sri-for':
            case 'sandbox':
                return empty($policies) ? '' : sprintf('%s %s', $directive, $policies);
        }

        // when policies is empty, we assume that user disallow this directive
        if (empty($policies)) {
            return sprintf("%s 'none'", $directive);
        }

        $ret = [$directive];

        // keyword source, https://www.w3.org/TR/CSP/#grammardef-keyword-source, https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/script-src
        foreach (['self', 'unsafe-inline', 'unsafe-eval', 'strict-dynamic', 'unsafe-hashed-attributes', 'report-sample'] as $keyword) {
            if (! empty($policies[$keyword])) {
                $ret[] = sprintf("'%s'", $keyword);
            }
        }

        if (! empty($policies['allow'])) {
            foreach ($policies['allow'] as $url) {
                // removes illegal URL characters
                if (false !== ($url = filter_var($url, FILTER_SANITIZE_URL))) {
                    $ret[] = $url;
                }
            }
        }

        if (! empty($policies['hashes'])) {
            foreach ($policies['hashes'] as $algo => $hashes) {
                // skip not support algorithm, https://www.w3.org/TR/CSP/#grammardef-hash-source
                if (! in_array($algo, ['sha256', 'sha384', 'sha512'])) {
                    continue;
                }

                foreach ($hashes as $value) {
                    // skip invalid value
                    if (! self::isBase64Valid($value)) {
                        continue;
                    }

                    $ret[] = sprintf("'%s-%s'", $algo, $value);
                }
            }
        }

        if (! empty($policies['nonces'])) {
            foreach ($policies['nonces'] as $nonce) {
                // skip invalid value, https://www.w3.org/TR/CSP/#grammardef-nonce-source
                if (! self::isBase64Valid($nonce)) {
                    continue;
                }

                $ret[] = sprintf("'nonce-%s'", $nonce);
            }
        }

        if (! empty($policies['schemes'])) {
            foreach ($policies['schemes'] as $scheme) {
                $ret[] = sprintf('%s', $scheme);
            }
        }

        return implode(' ', $ret);
    }

    /**
     * Check base64 encoded string is valid or not.
     *
     * @param string $encode
     *
     * @return bool
     */
    protected static function isBase64Valid(string $encode): bool
    {
        $decode = base64_decode($encode, true);

        if (false === $decode) {
            return false;
        }

        return base64_encode($decode) === $encode;
    }
}
