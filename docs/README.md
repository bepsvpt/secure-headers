# Document and Notice

* [Overall](#overall)
* [Clear Site Data](#clear-site-data)
* [HTTP Strict Transport Security](#http-strict-transport-security)
* [Public Key Pinning](#public-key-pinning)
* [Content Security Policy](#content-security-policy)

## Overall

* Each header in config file has a reference link in phpdoc, reading it will help you knowing what the header doing.
* If you want to disable a string type header, just set the value to `null` or empty string `''`.

## Clear Site Data

* Clear Site Data only supports `https` protocol, it will not work in `http` protocol.

## HTTP Strict Transport Security

* After setting `hsts` header, you can visit [https://hstspreload.org](https://hstspreload.org) and submit request to add your domain to `preload list`.

## Public Key Pinning

* When `hpkp` `hashes` array is empty, this header will not add to http response.

## Content Security Policy

* If you want to disable csp header, set `custom-csp` to empty string `''`.
* When a directive is empty array, it will set to `none`. If you want to omit a directive, just remove it.
* If you want to allow specific protocol in directive, add them to `schemes` array.
    ```php
    'img-src' => [
        'schemes' => [
            'data:',
            'https:',
        ],
    ],
    ```
* If you want to use automated generated nonce value, setting `add-generated-nonce` to `true` in `script-src` or `style-src` directives and calling `SecureHeaders::nonce()` to get nonce value.
