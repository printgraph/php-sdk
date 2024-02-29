<?php

declare(strict_types=1);

namespace Printgraph\PhpSdk;

use Printgraph\PhpSdk\Api\Pdf\Generator\Generator;
use Printgraph\PhpSdk\Client\ClientInterface;

class Printgraph
{
    public function __construct(private readonly ClientInterface $client) {}

    public function pdf(): Generator
    {
        return new Generator($this->client);
    }
}
