# Chatwork API Client

[![Build Status](https://travis-ci.org/kitchenu/chatwork-php.svg?branch=master)](https://travis-ci.org/kitchenu/chatwork-php)
[![License](https://poser.pugx.org/kitchenu/chatwork-php/license)](https://packagist.org/packages/kitchenu/chatwork-php)

ChatWork API Client for PHP.

## Installation

```bash
$ composer require kitchenu/chatwork-php
```
## Usage

```php
<?php

require 'vendor/autoload.php';

$client = new Kitchenu\Chatwork\Client('chatwork-api-token');

$response = $client->request('GET', 'me');

echo $response->mail;
// mail@example.com
```

How to call endpoint
POST  /rooms/{room_id}/messages

```php
$client->request('POST', 'rooms/123456789/messages', ['body' => 'Hello']);
```

or

```php
$client->postRoomMessages([
    'room_id' => '123456789',
    'body' => 'Hello'
]);
```

## Documentation

- [Chatwork API Document](http://developer.chatwork.com/ja/)