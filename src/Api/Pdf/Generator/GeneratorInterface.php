<?php

declare(strict_types=1);

namespace Printgraph\PhpSdk\Api\Pdf\Generator;

use Prewk\Result;
use Psr\Http\Message\ResponseInterface;

interface GeneratorInterface
{
    /**
     * PDF生成リクエストを実行
     *
     * @param GenerateRequest $generateRequest
     * @return Result<\Printgraph\PhpSdk\Client\Response\SuccessResponse, \Printgraph\PhpSdk\Client\Response\ErrorResponse>
     * @phpstan-return Result<\Printgraph\PhpSdk\Client\Response\SuccessResponse, \Printgraph\PhpSdk\Client\Response\ErrorResponse>
     *
     * @throws \Exception
     */
    public function generate(GenerateRequest $generateRequest): Result;
}
