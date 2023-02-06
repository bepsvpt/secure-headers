# Document and Notice

* [Overall](#overall)
* [Clear Site Data](#clear-site-data)
* [HTTP Strict Transport Security](#http-strict-transport-security)
* [Content Security Policy](#content-security-policy)
* [Testing](#testing)

## Overall

* Each header in config file has a reference link in phpdoc, reading it will help you knowing what the header doing.
* If you want to disable a string type header, just set the value to `null` or empty string `''`.

## Clear Site Data

* Clear Site Data only supports `https` protocol, it will not work in `http` protocol.

## HTTP Strict Transport Security

* After setting `hsts` header, you can visit [https://hstspreload.org](https://hstspreload.org) and submit request to add your domain to `preload list`.

## Content Security Policy

**You can find real world website examples in [tests](https://github.com/bepsvpt/secure-headers/blob/6.0.0/tests/Builders/ContentSecurityPolicyBuilderTest.php#L241-L945).**

**After setup csp, you should use [CSP Evaluator](https://csp-evaluator.withgoogle.com) to check up your setting.**

* If you want to allow specific protocol in directive:
    ```php
    'img-src' => [
        'schemes' => [
            'data:',
            'https:',
        ],
    ],
    ```

* If you want to use `nonce` in blade template:

    using inject
    ```blade
    @inject('headers', 'Bepsvpt\SecureHeaders\SecureHeaders')

    <style nonce="{{ $headers->nonce('style') }}">
      // your css
    </style>
    ```

    or calling directly
    ```blade
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
      // your js
    </script>
    ```
  
    or using helper function
    ```blade
    <script nonce="{{ csp_nonce('script') }}">
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
|   6.x   |    4.x    |   8.5   |        7.2.0        |
|   7.x   |    5.x    |   8.5   |        7.2.5        |
|   8.x   |    6.x    |   9.5   |         7.3         |
|   9.x   |    7.x    |   9.5   |         8.0         |
|  10.x   |    8.x    |   9.5   |         8.1         |
