# Secure Headers

[![Build Status](https://travis-ci.org/BePsvPT/secure-headers.svg?branch=master)](https://travis-ci.org/BePsvPT/secure-headers)
[![Test Coverage](https://codeclimate.com/github/BePsvPT/secure-headers/badges/coverage.svg)](https://codeclimate.com/github/BePsvPT/secure-headers/coverage)
[![Code Climate](https://codeclimate.com/github/BePsvPT/secure-headers/badges/gpa.svg)](https://codeclimate.com/github/BePsvPT/secure-headers)
[![StyleCI](https://styleci.io/repos/47176049/shield)](https://styleci.io/repos/47176049)
[![Latest Stable Version](https://poser.pugx.org/bepsvpt/secure-headers/v/stable?format=flat-square)](https://packagist.org/packages/bepsvpt/secure-headers)
[![Total Downloads](https://poser.pugx.org/bepsvpt/secure-headers/downloads?format=flat-square)](https://packagist.org/packages/bepsvpt/secure-headers)
[![License](https://poser.pugx.org/bepsvpt/secure-headers/license?format=flat-square)](https://packagist.org/packages/bepsvpt/secure-headers)

Add secure headers to HTTP response.

## Install

Install using composer

```sh
composer require bepsvpt/secure-headers
```

Add the service provider in `config/app.php`

```php
Bepsvpt\SecureHeaders\SecureHeadersServiceProvider::class,
```

Publish config file

```sh
php artisan vendor:publish --provider="Bepsvpt\SecureHeaders\SecureHeadersServiceProvider"
```

Add global middleware in `app/Http/Kernel.php`

```php
\Bepsvpt\SecureHeaders\SecureHeadersMiddleware::class,
```

Set up the config file `config/secure-headers.php`

Done!

## CHANGELOG

Please see [CHANGELOG](CHANGELOG.md) for details.

## UPGRADE

Please see [UPGRADE](UPGRADE.md) for details.

## License

Secure Headers is licensed under [The MIT License (MIT)](LICENSE).
