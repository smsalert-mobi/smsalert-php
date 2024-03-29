# smsalert-php

## Documentation

The documentation for the SMSAlert API can be found [here][apidocs].

The PHP library documentation can be found [here][libdocs].

This library supports the following PHP implementations:

* PHP 7.4 | ^8.0

## Installation

You can install **smsalert-phpp** via composer or by downloading the source.

### Via Composer:

**smsalert-php** is available on Packagist as the
[`smsalert-mobi/smsalert-php`](smsalert-mobi/smsalert-php) package:

```
composer require smsalert-mobi/smsalert-php
```

## Quickstart

### Send an SMS

```php
// Send an SMS using SMSAlert's REST API and PHP
<?php
$username = "demo"; // Your account username
$apiKey = "api_key_here"; // Your account apiKey from https://smsalert.mobi/settings

$client = new SmsAlert\SmsClient($username, $apiKey);
$messageId = $client->sendSms('0720123456', 'test api');

echo $messageId;
```

## Getting help

If you need help installing or using the library, you can contact us at contact@smsalert.mobi
If you've instead found a bug in the library or would like new features added, go ahead and open issues or pull requests against this repo!

[apidocs]: https://smsalert.mobi/apidocs/
[libdocs]: https://github.com/smsalert-mobi/smsalert-php
