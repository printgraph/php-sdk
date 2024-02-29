<?php

namespace Client;

use GuzzleHttp\ClientInterface as HttpClientInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Printgraph\PhpSdk\Client\Client;
use Prewk\Result\{Err, Ok};

final class ClientTest extends TestCase
{
    public function testRequestSuccess(): void
    {
        $httpClientMock = $this->createMock(HttpClientInterface::class);
        $httpClientMock->expects($this->once())
            ->method('request')
            ->with('GET', '/v1/test', [])
            ->willReturn(new Response(200, [], 'test'));

        $client = new Client($httpClientMock, 'v1');
        $response = $client->request('GET', 'test');
        self::assertInstanceOf(Ok::class, $response);
        self::assertEquals('test', $response->unwrap()->getBody()->getContents());
    }

    public function testRequestFailure(): void
    {
        $httpClientMock = $this->createMock(HttpClientInterface::class);
        $httpClientMock->expects($this->once())
            ->method('request')
            ->with('GET', '/v1/test', [])
            ->willReturn(new Response(500, [], 'error'));

        $client = new Client($httpClientMock, 'v1');
        $response = $client->request('GET', 'test');
        self::assertInstanceOf(Err::class, $response);
    }
}