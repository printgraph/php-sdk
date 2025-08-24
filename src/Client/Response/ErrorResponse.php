<?php

declare(strict_types=1);

namespace Printgraph\PhpSdk\Client\Response;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

/**
 * エラーレスポンスを表す値オブジェクト
 *
 * HTTPエラーレスポンス（4xx/5xx）とGuzzleException（接続エラーなど）の両方に対応
 *
 * @phpstan-immutable
 */
final class ErrorResponse
{
    private ?string $cachedErrorMessage = null;

    public function __construct(
        private readonly ?ResponseInterface $response = null,
        private readonly ?GuzzleException $exception = null,
    ) {
        if ($this->response === null && $this->exception === null) {
            throw new \InvalidArgumentException('Either response or exception must be provided');
        }
    }

    /**
     * HTTPレスポンスから生成
     */
    public static function fromResponse(ResponseInterface $response): self
    {
        return new self(response: $response);
    }

    /**
     * Guzzle例外から生成
     */
    public static function fromException(GuzzleException $exception): self
    {
        // RequestExceptionがレスポンスを持つ場合は、レスポンスも保存
        if ($exception instanceof \GuzzleHttp\Exception\RequestException
            && method_exists($exception, 'hasResponse')
            && $exception->hasResponse()
            && method_exists($exception, 'getResponse')) {
            return new self(
                response: $exception->getResponse(),
                exception: $exception
            );
        }

        return new self(exception: $exception);
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    public function getException(): ?GuzzleException
    {
        return $this->exception;
    }

    public function getStatusCode(): int
    {
        return $this->response?->getStatusCode() ?? 0;
    }

    public function getErrorMessage(): string
    {
        if ($this->cachedErrorMessage !== null) {
            return $this->cachedErrorMessage;
        }

        if ($this->response !== null) {
            $body = $this->response->getBody();
            if ($body->isSeekable()) {
                $body->rewind();
            }
            $this->cachedErrorMessage = $body->getContents();
            return $this->cachedErrorMessage;
        }

        $this->cachedErrorMessage = $this->exception?->getMessage() ?? 'Unknown error';
        return $this->cachedErrorMessage;
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
     * HTTP接続エラー（タイムアウト、DNS解決失敗など）の場合true
     */
    public function isConnectionError(): bool
    {
        // HTTPレスポンスがある場合は接続エラーではない
        if ($this->response !== null) {
            return false;
        }

        // RequestExceptionでレスポンスを持つ場合も接続エラーではない
        if ($this->exception instanceof \GuzzleHttp\Exception\RequestException
            && method_exists($this->exception, 'hasResponse')
            && $this->exception->hasResponse()) {
            return false;
        }

        // その他の例外は接続エラーとして扱う
        return $this->exception !== null;
    }

    /**
     * @return array<string, string[]>
     */
    public function getHeaders(): array
    {
        return $this->response?->getHeaders() ?? [];
    }
}
