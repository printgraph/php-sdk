<?php

declare(strict_types=1);

namespace Printgraph\PhpSdk\Api\Pdf\Generator;

use Prewk\Result;
use Printgraph\PhpSdk\Client\ClientInterface;
use Printgraph\PhpSdk\Client\Response\ErrorResponse;
use Printgraph\PhpSdk\Client\Response\SuccessResponse;

final class Generator implements GeneratorInterface
{
    public function __construct(private readonly ClientInterface $client) {}

    /**
     * @return Result<SuccessResponse, ErrorResponse>
     * @phpstan-return Result<SuccessResponse, ErrorResponse>
     * @throws \Exception
     */
    public function generate(GenerateRequest $generateRequest): Result
    {
        return $this->client->request('POST', 'v1/pdf/generate', [
            'json' => [
                'templateId' => $generateRequest->templateId,
                'params' => $generateRequest->params,
                'format' => $generateRequest->format,
            ],
            'headers' => [
                'Accept' => ['application/pdf', 'application/json'],
            ],
        ]);
    }

}
