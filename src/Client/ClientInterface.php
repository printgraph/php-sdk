<?php

namespace Printgraph\PhpSdk\Client;

use Prewk\Result;
use Psr\Http\Message\ResponseInterface;

interface ClientInterface
{
    /**
     * @param mixed[] $options
     * @return Result<ResponseInterface, ResponseInterface>
     */
    public function request(string $method, string $path, array $options = []): Result;
}