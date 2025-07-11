<?php

declare(strict_types=1);

namespace Printgraph\PhpSdk\Api\Pdf\Generator;

final class GenerateRequest
{
    /**
     * @param mixed[] $params
     */
    public function __construct(
        public readonly string $templateKey,
        public readonly array  $params,
    ) {}
}
