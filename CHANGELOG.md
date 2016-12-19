# Changelog for Secure Headers

## dev

## 3.x

- 3.0.0 (2016-12-19)
  - Support non laravel project
    - Remove env helper function from config file [d4379b0](https://github.com/BePsvPT/secure-headers/commit/d4379b052f3ffb5f0b45da967645d4bfe345014c#diff-47866b67d787728550e5ee35c73b17b5)
  - Change namespace
  - Change project name

## 2.x

- 2.2.0 (2016-10-03)
  - Add X-Download-Options, X-Permitted-Cross-Domain-Policies, Referrer-Policy headers

- 2.1.1 (2016-08-24)
  - Prevent testing pollute helper functions

- 2.1.0 (2016-08-24)
  - Revert config file from json format to php

- 2.0.0 (2016-08-18)
  - Transform config file to json format
  - Remove force https config
  - Increase minimum php version to 7.0

## 1.x

- 1.1.2 (2016-01-02)
  - Code refactoring
  - Debug mode will add csp header now
