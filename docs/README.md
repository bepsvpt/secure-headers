# Document and Notice

* [Overall](#overall)
* [Clear Site Data](#clear-site-data)
* [HTTP Strict Transport Security](#http-strict-transport-security)
* [Public Key Pinning](#public-key-pinning)
* [Content Security Policy](#content-security-policy)
* [Testing](#testing)

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
* If you want to use automated generated nonce value, setting `add-generated-nonce` to `true` in `script-src` or `style-src` directives and calling `Bepsvpt\SecureHeaders\SecureHeaders::nonce()` to get nonce value.

    using inject
    ```blade
    @inject('headers', 'Bepsvpt\SecureHeaders\SecureHeaders')

    <style nonce="{{ $headers->nonce() }}">
      // your css
    </style>
    ```

    or calling directly
    ```blade
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce() }}">
      // your js
    </script>
    ```

## Testing

If you want to run testing, make sure you have the corresponding package version and minimum php version.

| Laravel | Testbench | PHPUnit | Minimum PHP Version |
|:-------:|:---------:|:-------:|:-------------------:|
|   5.1   |    3.1    |   5.7   |         7.0         |
|   5.2   |    3.2    |   5.7   |         7.0         |
|   5.3   |    3.3    |   5.7   |         7.0         |
|   5.4   |    3.4    |   6.5   |         7.0         |
|   5.5   |    3.5    |   6.5   |         7.0         |
|   5.6   |    3.6    |   7.5   |        7.1.3        |
|   5.7   |    3.7    |   7.5   |        7.1.3        |
|   5.8   |    3.8    |   7.5   |        7.1.3        |
