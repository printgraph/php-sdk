<?php

declare(strict_types=1);

namespace Printgraph\PhpSdk\Api\Pdf\Generator;

use Prewk\Result;
use Prewk\Result\Err;
use Printgraph\PhpSdk\Client\ClientInterface;
use Printgraph\PhpSdk\Client\Response\ErrorResponse;
use Printgraph\PhpSdk\Client\Response\SuccessResponse;

final class Generator implements GeneratorInterface
{
    public function __construct(private readonly ClientInterface $client) {}

    /**
     * @return Result<SuccessResponse, ErrorResponse|ValidationError>
     * @phpstan-return Result<SuccessResponse, ErrorResponse|ValidationError>
     * @throws \Exception
     */
    public function generate(GenerateRequest $generateRequest): Result
    {
        $validationResult = $generateRequest->validate();

        if ($validationResult->isErr()) {
            /** @var ValidationError $validationError */
            $validationError = $validationResult->unwrapErr();
            /** @var Result<SuccessResponse, ErrorResponse|ValidationError> */
            return new Err($validationError);
        }

        /** @var Result<SuccessResponse, ErrorResponse|ValidationError> */
        return $this->client->request('POST', 'v1/pdf/generate', [
            'json' => [
                'templateId' => $generateRequest->templateId,
                'params' => empty($generateRequest->params) ? new \stdClass() : $generateRequest->params,
                'format' => $generateRequest->format,
            ],
            'headers' => [
                'Accept' => ['application/pdf', 'application/json'],
            ],
        ]);
    }

}
