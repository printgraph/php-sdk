<?php

declare(strict_types=1);

namespace Printgraph\PhpSdk\Client\Response;

use Psr\Http\Message\ResponseInterface;

/**
 * エラーレスポンスを表す値オブジェクト
 *
 * @phpstan-immutable
 */
final class ErrorResponse
{
    public function __construct(
        private readonly ResponseInterface $response,
    ) {}

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    public function getErrorMessage(): string
    {
        return $this->response->getBody()->getContents();
    }

    public function isClientError(): bool
    {
        $code = $this->getStatusCode();
        return $code >= 400 && $code < 500;
    }

    public function isServerError(): bool
    {
        $code = $this->getStatusCode();
        return $code >= 500 && $code < 600;
    }

    /**
     * @return array<string, string[]>
     */
    public function getHeaders(): array
    {
        return $this->response->getHeaders();
    }
}
