# ActiveCampaign API SDK

[![Tests status](https://github.com/metaline/activecampaign-sdk/actions/workflows/tests.yml/badge.svg)](https://github.com/metaline/activecampaign-sdk/actions)
[![Coverage Status](https://coveralls.io/repos/github/metaline/activecampaign-sdk/badge.svg?branch=master)](https://coveralls.io/github/metaline/activecampaign-sdk?branch=master)

This library is a simple PHP wrapper for the [ActiveCampaign API v3](https://developers.activecampaign.com/v3/reference).

## Installation

Install the latest version with [Composer](https://getcomposer.org/):

```
composer require metaline/activecampaign-sdk
```

### Requirements

This project works with PHP 5.6+ or 7.1+.

You also need a URL and a KEY to access the ActiveCampaign APIs. These parameters are specific
to your ActiveCampaign account. You can find them under Settings / Developer section of your
profile.

## Documentation

First you need to create an instance of the Client:

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use MetaLine\ActiveCampaign\Client;

$apiURL = 'https://<YOUR ACCOUNT>.api-us1.com';
$apiKEY = 'super-secret-key'; // Never publish this key!

$client = new Client($apiURL, $apiKEY);
```

Now you are ready to talk to the ActiveCampaign API. For example, you can [retrieves all contacts](https://developers.activecampaign.com/v3/reference#list-all-contacts):

```php
$result = $client->get('contacts');
```

Or [create a new contact](https://developers.activecampaign.com/v3/reference#create-contact):

```php
$result = $client->post('contacts', [
    'contact' => [
        'email'     => 'johndoe@example.com',
        'firstName' => 'John',
        'lastName'  => 'Doe',
        'phone'     => '7223224241',
    ]
]);
```

Or [delete an existing one](https://developers.activecampaign.com/v3/reference#delete-contact):

```php
$result = $client->delete('contacts/' . $contactId);
```

And so on.

Check the ActiveCampaign [documentation](https://developers.activecampaign.com/v3/reference) for the other APIs.

### Work with the Result object

All Client methods return a `Result` object, that it’s a simple value object:

```php
if ($result->isSuccessful()) {
	$data = $result->getData();
} else {
	$errors = $result->getErrors();
}
```

Debug the result to discover how to proceed.

## License

This project is licensed under the MIT License. See the LICENSE file for details.
