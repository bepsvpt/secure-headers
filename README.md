# Secure Headers

[![Build Status](https://travis-ci.org/BePsvPT/secure-headers.svg?branch=master)](https://travis-ci.org/BePsvPT/secure-headers)
[![Test Coverage](https://codeclimate.com/github/BePsvPT/secure-headers/badges/coverage.svg)](https://codeclimate.com/github/BePsvPT/secure-headers/coverage)
[![Code Climate](https://codeclimate.com/github/BePsvPT/secure-headers/badges/gpa.svg)](https://codeclimate.com/github/BePsvPT/secure-headers)
[![StyleCI](https://styleci.io/repos/47176049/shield)](https://styleci.io/repos/47176049)
[![Latest Stable Version](https://poser.pugx.org/bepsvpt/secure-headers/v/stable?format=flat-square)](https://packagist.org/packages/bepsvpt/secure-headers)
[![Total Downloads](https://poser.pugx.org/bepsvpt/secure-headers/downloads?format=flat-square)](https://packagist.org/packages/bepsvpt/secure-headers)
[![License](https://poser.pugx.org/bepsvpt/secure-headers/license?format=flat-square)](https://packagist.org/packages/bepsvpt/secure-headers)

Add security related headers to HTTP response. The package includes Service Providers for easy [Laravel](https://laravel.com) integration.

- [Version](#version)
- [Installation](#installation)
- [Usage（non laravel project）](#usage)
- [Document and Notice](#document-and-notice)
- [Changelog](#changelog)
- [Upgrade](#upgrade)
- [License](#license)

## Version

5.3.3

### Supported Laravel Version

5.1 ~ 5.8

## Installation

### Non Laravel Project

Install using composer

```sh
composer require bepsvpt/secure-headers
```

Copy config file to your project directory

```sh
cp vendor/bepsvpt/secure-headers/config/secure-headers.php path/to/your/project/directory
```

Set up config file

Done!

### Laravel Project

Install using composer

```sh
composer require bepsvpt/secure-headers
```

Add service provider in `config/app.php` ( laravel version < 5.5 )

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

Set up config file `config/secure-headers.php`

Done!

### Lumen Project

Install using composer

```sh
composer require bepsvpt/secure-headers
```

Add service provider in `bootstrap/app.php`

```php
$app->register(Bepsvpt\SecureHeaders\SecureHeadersServiceProvider::class);
```

Copy config file to project directory

```sh
mkdir config
cp vendor/bepsvpt/secure-headers/config/secure-headers.php config/secure-headers.php
```

Set up config file `config/secure-headers.php`

Done!


## Usage

### Non Laravel Project

**Do not forget to import namespace.**

```php
<?php

use \Bepsvpt\SecureHeaders\SecureHeaders;
```

#### Instance

```php
<?php

// instantiate the class by fromFile static method
$secureHeaders = SecureHeaders::fromFile('/path/to/secure-headers.php');

// or instantiate the class directly
$config = require '/path/to/secure-headers.php';

$config['key'] = 'value'; // modify config value if you need

$secureHeaders = new SecureHeaders($config);
```

#### Send
```php
// Get headers
$secureHeaders->headers();

// Send headers to HTTP response
$secureHeaders->send();
```

## Document and Notice

Please see [DOCS](docs) for details.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for details.

## Upgrade

Please see [UPGRADE](UPGRADE.md) for details.

## License

Secure Headers is licensed under [The MIT License (MIT)](LICENSE).
