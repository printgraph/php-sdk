<?php

declare(strict_types=1);

namespace Printgraph\PhpSdk\Tests\Client;

use GuzzleHttp\ClientInterface as HttpClientInterface;
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
    }
}
