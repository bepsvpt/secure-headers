# Laravel Security Header

[![Build Status](https://travis-ci.org/BePsvPT/laravel-security-header.svg?branch=master)](https://travis-ci.org/BePsvPT/laravel-security-header)
[![StyleCI](https://styleci.io/repos/47176049/shield)](https://styleci.io/repos/47176049)
[![Latest Stable Version](https://poser.pugx.org/bepsvpt/laravel-security-header/v/stable?format=flat-square)](https://packagist.org/packages/bepsvpt/laravel-security-header)
[![Total Downloads](https://poser.pugx.org/bepsvpt/laravel-security-header/downloads?format=flat-square)](https://packagist.org/packages/bepsvpt/laravel-security-header)
[![License](https://poser.pugx.org/bepsvpt/laravel-security-header/license?format=flat-square)](https://packagist.org/packages/bepsvpt/laravel-security-header)

Add security headers to http response for laravel framework.

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

## License

Laravel Security Header is licensed under [The MIT License (MIT)](LICENSE).
