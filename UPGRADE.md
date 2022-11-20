## 7.x.x to 7.3.0

- The following new headers are added, you can find it [here](https://github.com/bepsvpt/secure-headers/blob/7.3.0/config/secure-headers.php#L89-L114) and copy to your config file.
  - `Cross-Origin-Embedder-Policy`
  - `Cross-Origin-Opener-Policy`
  - `Cross-Origin-Resource-Policy`

## 6.2.0 to 7.0.0

- `feature-policy` was replaced with `permissions-policy`, make sure you add `permissions-policy` config to the config file, you can find it [here](https://github.com/bepsvpt/secure-headers/blob/7.0.0/config/secure-headers.php#L139-L433).

## 6.1.x to 6.2.0

- Add `use-permissions-policy-header` config key for `feature-policy`, you can find it [here](https://github.com/bepsvpt/secure-headers/blob/6.2.0/config/secure-headers.php#L148-L159).

## 6.0.x to 6.1.0

- `X-Power-By` header renamed to `X-Powered-By`.

## 5.x.x to 6.0.0

- Lumen user need to add SecureHeadersMiddleware manually.
- HSTS preload is disabled by default now, if your HSTS config does not contain `preload` key and you want to preserve previous behavior, add `preload` to HSTS section and set to `true`.
- Update `csp` config structure from [config file](https://github.com/bepsvpt/secure-headers/blob/6.0.0/config/secure-headers.php).

## 5.4.0 to 5.5.0

- The following new headers are added, you can find it [here](https://github.com/bepsvpt/secure-headers/blob/5.5.0/config/secure-headers.php#L55-L61) and copy to your config file.
  - `X-Power-By`

## 5.3.x to 5.4.0

- HSTS `preload` field can be disabled now, you can find it [here](https://github.com/bepsvpt/secure-headers/blob/5.4.0/config/secure-headers.php#L111) and copy to your config file.
- `display-capture` and `document-domain` are added to Feature-Policy, you can find it [here](https://github.com/bepsvpt/secure-headers/blob/5.4.0/config/secure-headers.php#L226-L238) and [here](https://github.com/bepsvpt/secure-headers/blob/5.4.0/config/secure-headers.php#L240-L252).

## 5.2.x to 5.3.0

- The following new headers are added, you can find it [here](https://github.com/bepsvpt/secure-headers/blob/5.3.0/config/secure-headers.php#L150-L335) and copy to your config file.
  - `Feature-Policy`

## 5.1.0 to 5.2.0

- The following new headers are added, you can find it [here](https://github.com/bepsvpt/secure-headers/blob/5.2.0/config/secure-headers.php#L5-L13) and [here](https://github.com/bepsvpt/secure-headers/blob/5.2.0/config/secure-headers.php#L76-L94) and copy to your config file.
  - `Clear-Site-Data`
  - `Server`

## 5.0.0 to 5.1.0

- The following new headers are added, you can find it [here](https://github.com/bepsvpt/secure-headers/blob/5.1.0/config/secure-headers.php#L82-L96) and copy to your config file.
  - `Expect-CT`

## 4.x.x to 5.0.0

- HPKP `hashes` field only supports sha256 algorithm, change other algorithms to sha256.
- CSP `https-transform-on-https-connections` was removed, dont forget to use the explicit protocol.
- CSP `child-src` directive was removed, use `frame-src` or `worker-src` directive instead.
- CSP `img-src` directive `data` field was removed, use `schemes` field instead.
- CSP directive `hashes` field has new format, you can find it [here](https://github.com/bepsvpt/secure-headers/blob/5.0.0/config/secure-headers.php#L137-L141).

## 3.x.x to 4.0.0

- If you are a Lumen user, change `$app->register(Bepsvpt\SecureHeaders\LumenServiceProvider::class);` to `$app->register(Bepsvpt\SecureHeaders\SecureHeadersServiceProvider::class);` in `bootstrap/app.php`
- Because of dependency changing, please check your Content-Security-Policy(CSP) header is correct after upgrade.

## 2.2.0 to 3.0.0

- Rename `config/security-header.php` to `config/secure-headers.php`
- Change provider from `Bepsvpt\LaravelSecurityHeader\SecurityHeaderServiceProvider::class,` to `Bepsvpt\SecureHeaders\SecureHeadersServiceProvider::class,` in `config/app.php`
- Change middleware from `\Bepsvpt\LaravelSecurityHeader\SecurityHeaderMiddleware::class,` to `\Bepsvpt\SecureHeaders\SecureHeadersMiddleware::class,` in `app/Http/Kernel.php`

## 2.1.x to 2.2.0

- The following new headers are added, you can find it [here](https://github.com/bepsvpt/secure-headers/blob/2.2.0/config/security-header.php) and copy to your config file.
  - `X-Download-Options`
  - `X-Permitted-Cross-Domain-Policies`
  - `Referrer-Policy`

## 2.0.0 to 2.1.0

- You need to republish the config file and set up according to your need.
