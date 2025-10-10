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
     * すべてのGuzzleExceptionを捕捉し、例外を発生させずにErr型で返す
     * - HTTPエラー（4xx/5xx）: ErrorResponse::fromResponse()で処理
     * - 接続エラー: ErrorResponse::fromException()で処理
     *
     * @return Result<Response\SuccessResponse, Response\ErrorResponse>
     * @phpstan-return Result<Response\SuccessResponse, Response\ErrorResponse>
     */
    public function request(string $method, string $path, array $options = []): Result
    {
        try {
            $response = $this->httpClient->request($method, $path, $options);

            if ($response->getStatusCode() >= 400) {
                return new Err(Response\ErrorResponse::fromResponse($response));
            }

            return new Ok(new Response\SuccessResponse($response));
        } catch (GuzzleException $exception) {
            return new Err(Response\ErrorResponse::fromException($exception));
        }
    }
}
