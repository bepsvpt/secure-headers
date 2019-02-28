## dev

## 5.x

- 5.3.3 (2019-02-28)
  - Support Laravel 5.8
  - Let Travis CI cover all supported PHP and Laravel versions

- 5.3.2 (2018-10-26)
  - Support Feature-Policy sync-xhr directive

- 5.3.1 (2018-09-06)
  - Support Laravel 5.7

- 5.3.0 (2018-08-04)
  - Support Feature-Policy header

- 5.2.1 (2018-07-31)
  - Update document

- 5.2.0 (2018-06-09)
  - Support Clear-Site-Header header
  - Support Server header

- 5.1.0 (2018-05-07)
  - Support Expect-CT header

- 5.0.0 (2018-04-09)
  - Support Content Security Policy Level 3.
  - Change HPKP `hashes` field scheme.
  - Change CSP directive `hashes` field scheme.
  - Rename CSP directive `type` field to `schemes`.
  - Remove CSP `https-transform-on-https-connections` directive.
  - Remove CSP `image-src` directive `data` field.
  - Do not use another packages for build csp and hpkp header.

## 4.x

- 4.2.0 (2018-03-11)
  - Support generating nonce value for CSP header ([#13](https://github.com/BePsvPT/secure-headers/pull/13))
  - Fix HSTS "preload" markup ([#16](https://github.com/BePsvPT/secure-headers/pull/16))

- 4.1.0 (2017-09-01)
  - Support Laravel 5.5 ([1f76e6a](https://github.com/BePsvPT/secure-headers/commit/1f76e6aca72eeab59f42000f06388cc684880a64))

- 4.0.0 (2017-08-04)
  - Transform [paragonie/csp-builder](https://github.com/paragonie/csp-builder) dependency to [bepsvpt/csp-builder](https://github.com/BePsvPT/csp-builder) ([4ce4f14](https://github.com/BePsvPT/secure-headers/commit/4ce4f14e938f47bf480f823914dfea3737bdae0c))
  - Transform [paragonie/hpkp-builder](https://github.com/paragonie/hpkp-builder) dependency to [bepsvpt/hpkp-builder](https://github.com/BePsvPT/hpkp-builder) ([4b69514](https://github.com/BePsvPT/secure-headers/commit/4b69514071ac90951a72daf5aca9c290837244ac)) ([da7091e](https://github.com/BePsvPT/secure-headers/commit/da7091e076ae805e711ac1737ddb0e30ff3d5fa8))

## 3.x

- 3.1.0 (2017-07-18)
  - Support Lumen framework ([ddc61b1](https://github.com/BePsvPT/secure-headers/commit/ddc61b13ed6ddaf4b6f83fc814936fb24741adbe))

- 3.0.7 (2017-05-16)
  - Change referrer-policy header default value ([8367d29](https://github.com/BePsvPT/secure-headers/commit/8367d29962816b737f0f21519a3603abc848d589))

- 3.0.6 (2017-04-20)
  - Use set method instead of excluding class when adds headers ([#7](https://github.com/BePsvPT/secure-headers/issues/7)) ([#8](https://github.com/BePsvPT/secure-headers/issues/8)) ([1455748](https://github.com/BePsvPT/secure-headers/commit/1455748c95ed839386465d72942a7014e7b3dd6b))

- 3.0.5 (2017-04-09)
  - Fix call to undefined method when download file ([#5](https://github.com/BePsvPT/secure-headers/issues/5)) ([5b7ccd3](https://github.com/BePsvPT/secure-headers/commit/5b7ccd395ce3e2feefbb51af1bd1d46532992f0c))

- 3.0.4 (2017-02-21)
  - Fix Laravel 5.1 compatibility ([#3](https://github.com/BePsvPT/secure-headers/pull/3))

- 3.0.3 (2017-01-29)
  - Support Laravel 5.4 ([a98840e](https://github.com/BePsvPT/secure-headers/commit/a98840e95bb476a8e104c249514ef1d7f97397ed))

- 3.0.2 (2017-01-23)
  - Support disable specific header ([9d995f7](https://github.com/BePsvPT/secure-headers/commit/9d995f76f7e301f921546f6446db113f50883082))

- 3.0.1 (2017-01-19)
  - Remove incorrect dependents ([64e0f93](https://github.com/BePsvPT/secure-headers/commit/64e0f939af8f85972038ede5051565cb1bcf4d11))

- 3.0.0 (2016-12-19)
  - Support non Laravel project
    - Remove env helper function from config file ([d4379b0](https://github.com/BePsvPT/secure-headers/commit/d4379b052f3ffb5f0b45da967645d4bfe345014c#diff-47866b67d787728550e5ee35c73b17b5))
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
