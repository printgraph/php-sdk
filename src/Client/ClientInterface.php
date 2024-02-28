<?php

namespace Printgraph\PhpSdk\Client;

use Prewk\Result;

interface ClientInterface
{
    public function request(string $method, string $path, array $options = []): Result;
}