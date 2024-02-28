<?php

namespace Printgraph\PhpSdk\Api\Pdf\Generator;

use Prewk\Result;
use Psr\Http\Message\ResponseInterface;

interface GeneratorInterface
{
    /**
     *
     * @param GenerateRequest $generateRequest
     * @return Result<ResponseInterface, ResponseInterface>
     */
    public function generate(GenerateRequest $generateRequest): Result;
}