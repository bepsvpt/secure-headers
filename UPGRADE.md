# UPGRADE

### 2.2.0 to 3.0.0

- Rename `config/security-header.php` to `config/secure-headers.php`
- Change provider from `Bepsvpt\LaravelSecurityHeader\SecurityHeaderServiceProvider::class,` to `Bepsvpt\SecureHeaders\SecureHeadersServiceProvider::class,` in `config/app.php`
- Change middleware from `\Bepsvpt\LaravelSecurityHeader\SecurityHeaderMiddleware::class,` to `\Bepsvpt\SecureHeaders\SecureHeadersMiddleware::class,` in `app/Http/Kernel.php`

### 2.1.x to 2.2.0

- The following new headers are added, you can find it [here](https://github.com/BePsvPT/laravel-security-header/blob/655c007418ac03bb56e152f5f5bfe6f7117a964b/config/security-header.php) and copy to your config file.
  - X-Download-Options
  - X-Permitted-Cross-Domain-Policies
  - Referrer-Policy

### 2.0.0 to 2.1.0

- You need to republish the config file and set up according to your need.
