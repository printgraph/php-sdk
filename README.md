# Printgraph PHP SDK

Printgraph API v1 client library, written in PHP

## Requirements

PHP 8.1 or later

## Installation

```
$ composer req printgraph/php-sdk
```

## Usage

```php
<?php

use Printgraph\PhpSdk\Api\Pdf\Generator\GenerateRequest;
use Printgraph\PhpSdk\Client\ClientFactory;
use Printgraph\PhpSdk\Printgraph;

require 'vendor/autoload.php';

$pringraph = new Printgraph(
    ClientFactory::createHttpClient('Ns7N39j0.VBQxTfp-LCUXlEKRaXpCONWGx9IAplbaa5VEvlPD89OVrJQ2dM-F60cm8')
);
$request = new GenerateRequest(
    'template',
    ['message' => 'Hello, World']
);

$response = $pringraph->pdf()->generate($request)->expect(
    new \RuntimeException('Failed to generate PDF')
);

file_put_contents('test.pdf', $response->getBody()->getContents());
```