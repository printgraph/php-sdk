<?php

declare(strict_types=1);

namespace Printgraph\PhpSdk\Client;

use Prewk\Result;
use Psr\Http\Message\ResponseInterface;

interface ClientInterface
{
    /**
     * HTTPリクエストを実行し、型安全なResult型で結果を返す
     *
     * @param string $method HTTPメソッド
     * @param string $path リクエストパス
     * @param mixed[] $options Guzzleオプション
     * @return Result<Response\SuccessResponse, Response\ErrorResponse>
     *
     * @phpstan-return Result<Response\SuccessResponse, Response\ErrorResponse>
     */
    public function request(string $method, string $path, array $options = []): Result;
}
