# docfalcon-sdk-php
DocFalcon SDK for PHP

## Introduction

This library provides PHP integration for [DocFalcon](https://www.docfalcon.com/) APIs.

We welcome [feedback and issues](https://github.com/docfalcon/docfalcon-sdk-php/issues) you may spot while using it. 

## Installation

```
TODO
```

## Usage

DocFalcon has one very simple and intuitive API for PDF generation. 
This library is a very simple wrapper around an http client.

### PDF Generation

```php
<?php

$businessCard = json_decode(file_get_contents('./samples/business_card.json'));

$client = new \GuzzleHttp\Client();
$docfalcon = new \DocFalcon\Client($client,'YOUR_APIKEY');
$response = $docfalcon->generate($businessCard, './business_card.pdf');
```

You can get more info about how to get an apikey or how to describe your document by looking at the [docs](https://www.docfalcon.com/docs).

## License 
BSD 3-Clause License

Copyright (c) 2017, DocFalcon