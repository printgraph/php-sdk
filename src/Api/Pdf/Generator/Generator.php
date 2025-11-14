<?php

declare(strict_types=1);

namespace Printgraph\PhpSdk\Api\Pdf\Generator;

use Prewk\Result;
use Printgraph\PhpSdk\Client\ClientInterface;

final class Generator implements GeneratorInterface
{
    public function __construct(private readonly ClientInterface $client) {}

    /**
     * @throws \Exception
     */
    public function generate(GenerateRequest $generateRequest): Result
    {
        return $this->client->request('POST', 'v1/pdf/generate', [
            'json' => [
                'templateKey' => $generateRequest->templateKey,
                'params' => $generateRequest->params,
            ],
            'headers' => [
                'Accept' => ['application/pdf', 'application/json'],
            ],
        ]);
    }

}
