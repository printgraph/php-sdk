<?php

namespace Printgraph\PhpSdk\Api\Pdf\Generator;

use Prewk\Result;

interface GeneratorInterface
{
    public function generate(GenerateRequest $generateRequest): Result;
}