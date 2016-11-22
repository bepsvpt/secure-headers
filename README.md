# Laravel Security Header

[![Build Status](https://travis-ci.org/BePsvPT/laravel-security-header.svg?branch=master)](https://travis-ci.org/BePsvPT/laravel-security-header)
[![Test Coverage](https://codeclimate.com/github/BePsvPT/laravel-security-header/badges/coverage.svg)](https://codeclimate.com/github/BePsvPT/laravel-security-header/coverage)
[![Code Climate](https://codeclimate.com/github/BePsvPT/laravel-security-header/badges/gpa.svg)](https://codeclimate.com/github/BePsvPT/laravel-security-header)
[![StyleCI](https://styleci.io/repos/47176049/shield)](https://styleci.io/repos/47176049)
[![Latest Stable Version](https://poser.pugx.org/bepsvpt/laravel-security-header/v/stable?format=flat-square)](https://packagist.org/packages/bepsvpt/laravel-security-header)
[![Total Downloads](https://poser.pugx.org/bepsvpt/laravel-security-header/downloads?format=flat-square)](https://packagist.org/packages/bepsvpt/laravel-security-header)
[![License](https://poser.pugx.org/bepsvpt/laravel-security-header/license?format=flat-square)](https://packagist.org/packages/bepsvpt/laravel-security-header)

Add secure headers to response for laravel framework.

## Install

Install using composer

```sh
composer require bepsvpt/laravel-security-header
```

Add the service provider in `config/app.php`

```php
Bepsvpt\LaravelSecurityHeader\SecurityHeaderServiceProvider::class,
```

Publish config file

```sh
php artisan vendor:publish --provider="Bepsvpt\LaravelSecurityHeader\SecurityHeaderServiceProvider"
```

Add global middleware in `app/Http/Kernel.php`

```php
\Bepsvpt\LaravelSecurityHeader\SecurityHeaderMiddleware::class,
```

Set up the config file `config/security-header.php`

Done!

## CHANGELOG

Please see [CHANGELOG](CHANGELOG.md) for details.

## UPGRADE

Please see [UPGRADE](UPGRADE.md) for details.

## License

Laravel Security Header is licensed under [The MIT License (MIT)](LICENSE).
