# Document for Headers in Config File

* [Policy](#policy)
* [Public Key Pinning](#public-key-pinning)
* [Content Security Policy](#content-security-policy)
* [Additional Resources](#additional-resources)

## Policy

* Each header has a reference link in config file, you should read it if you do not know the header.
* If you want to disable a string type header, just set to `null` or empty string.

## Public Key Pinning

* When hashes is empty array, this header will not add to http response.

## Content Security Policy

* We use [paragonie/csp-builder](https://github.com/paragonie/csp-builder) to help us support csp header.
* If you want to disable csp header, set `custom-csp` to empty string.

## Additional Resources

* [Everything you need to know about HTTP security headers](https://blog.appcanary.com/2017/http-security-headers.html)
