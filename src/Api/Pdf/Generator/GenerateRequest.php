<?php

namespace Printgraph\PhpSdk\Api\Pdf\Generator;

final class GenerateRequest
{
    /**
     * @param mixed[] $params
     */
    public function __construct(
        public readonly string $template,
        public readonly array  $params,
    )
    {
    }
}