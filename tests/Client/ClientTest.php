<?php

declare(strict_types=1);

namespace Printgraph\PhpSdk\Tests\Client;

use GuzzleHttp\ClientInterface as HttpClientInterface;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Printgraph\PhpSdk\Client\Client;
use Prewk\Result\{Err, Ok};
use Psr\Http\Message\ResponseInterface;

final class ClientTest extends TestCase
{
    public function testRequestSuccess(): void
    {
        $httpClientMock = $this->createMock(HttpClientInterface::class);
        $httpClientMock->expects($this->once())
            ->method('request')
            ->with('GET', '/test', [])
            ->willReturn(new Response(200, [], 'test'));

        $client = new Client($httpClientMock);
        $result = $client->request('GET', '/test');
        self::assertInstanceOf(Ok::class, $result);

        /** @var \Printgraph\PhpSdk\Client\Response\SuccessResponse $response */
        $response = $result->unwrap();
        self::assertInstanceOf(\Printgraph\PhpSdk\Client\Response\SuccessResponse::class, $response);
        self::assertEquals('test', $response->getContents());
    }

    public function testRequestFailure(): void
    {
        $httpClientMock = $this->createMock(HttpClientInterface::class);
        $httpClientMock->expects($this->once())
            ->method('request')
            ->with('GET', '/test', [])
            ->willReturn(new Response(400, [], 'Bad Request'));

        $client = new Client($httpClientMock);
        $response = $client->request('GET', '/test');
        self::assertInstanceOf(Err::class, $response);

        /** @var \Printgraph\PhpSdk\Client\Response\ErrorResponse $error */
        $error = $response->unwrapErr();
        self::assertInstanceOf(\Printgraph\PhpSdk\Client\Response\ErrorResponse::class, $error);
        self::assertEquals(400, $error->getStatusCode());
        self::assertTrue($error->isClientError());
        self::assertFalse($error->isServerError());
        self::assertFalse($error->isConnectionError());
        self::assertNotNull($error->getResponse());
    }

    public function testRequestConnectionError(): void
    {
        $httpClientMock = $this->createMock(HttpClientInterface::class);
        $request = new Request('GET', '/test');
        $exception = new ConnectException('Connection timeout', $request);

        $httpClientMock->expects($this->once())
            ->method('request')
            ->with('GET', '/test', [])
            ->willThrowException($exception);

        $client = new Client($httpClientMock);
        $result = $client->request('GET', '/test');
        self::assertInstanceOf(Err::class, $result);

        /** @var \Printgraph\PhpSdk\Client\Response\ErrorResponse $error */
        $error = $result->unwrapErr();
        self::assertInstanceOf(\Printgraph\PhpSdk\Client\Response\ErrorResponse::class, $error);
        self::assertTrue($error->isConnectionError());
        self::assertFalse($error->isClientError());
        self::assertFalse($error->isServerError());
        self::assertEquals(0, $error->getStatusCode());
        self::assertEquals('Connection timeout', $error->getErrorMessage());
        self::assertNull($error->getResponse());
        self::assertSame($exception, $error->getException());
    }

    public function testRequestExceptionWithResponse(): void
    {
        $httpClientMock = $this->createMock(HttpClientInterface::class);
        $request = new Request('GET', '/test');
        $response = new Response(500, [], 'Internal Server Error');
        $exception = new RequestException('Server error', $request, $response);

        $httpClientMock->expects($this->once())
            ->method('request')
            ->with('GET', '/test', [])
            ->willThrowException($exception);

        $client = new Client($httpClientMock);
        $result = $client->request('GET', '/test');
        self::assertInstanceOf(Err::class, $result);

        /** @var \Printgraph\PhpSdk\Client\Response\ErrorResponse $error */
        $error = $result->unwrapErr();
        self::assertInstanceOf(\Printgraph\PhpSdk\Client\Response\ErrorResponse::class, $error);

        // 修正：レスポンスがあるので接続エラーではない
        self::assertFalse($error->isConnectionError());
        self::assertTrue($error->isServerError());
        self::assertEquals(500, $error->getStatusCode());
        self::assertEquals('Internal Server Error', $error->getErrorMessage());
        self::assertNotNull($error->getResponse());
        self::assertSame($exception, $error->getException());
    }
}
