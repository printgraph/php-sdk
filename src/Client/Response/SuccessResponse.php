<?php

declare(strict_types=1);

namespace Printgraph\PhpSdk\Client\Response;

use Psr\Http\Message\ResponseInterface;

/**
 * 成功レスポンスを表す値オブジェクト
 *
 * @phpstan-immutable
 */
final class SuccessResponse
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

    public function getContents(): string
    {
        return $this->response->getBody()->getContents();
    }

    /**
     * @return array<string, string[]>
     */
    public function getHeaders(): array
    {
        return $this->response->getHeaders();
    }
}
