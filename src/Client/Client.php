<?php

declare(strict_types=1);

namespace Printgraph\PhpSdk\Client;

use GuzzleHttp\ClientInterface as HttpClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Prewk\Result;
use Prewk\Result\{Err, Ok};

final class Client implements ClientInterface
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
    ) {}

    /**
     * HTTPリクエストを実行し、型安全なResult型で結果を返す
     *
     * @throws GuzzleException
     * @return Result<Response\SuccessResponse, Response\ErrorResponse>
     * @phpstan-return Result<Response\SuccessResponse, Response\ErrorResponse>
     */
    public function request(string $method, string $path, array $options = []): Result
    {
        $response = $this->httpClient->request($method, $path, $options);
        if ($response->getStatusCode() >= 400) {
            return new Err(new Response\ErrorResponse($response));
        }

        return new Ok(new Response\SuccessResponse($response));
    }
}
