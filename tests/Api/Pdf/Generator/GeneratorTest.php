<?php

declare(strict_types=1);

namespace Printgraph\PhpSdk\Tests\Api\Pdf\Generator;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Prewk\Result\{Ok, Err};
use Printgraph\PhpSdk\Api\Pdf\Generator\GenerateRequest;
use Printgraph\PhpSdk\Api\Pdf\Generator\Generator;
use Printgraph\PhpSdk\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

final class GeneratorTest extends TestCase
{
    public function testGeneratorRequestSuccess(): void
    {
        $mockClient = $this->createMock(ClientInterface::class);
        $mockClient
            ->expects($this->once())
            ->method('request')
            ->with('POST', 'v1/pdf/generate', [
                'json' => [
                    'templateKey' => 'template',
                    'params' => ['param1' => 'value1', 'param2' => 'value2'],
                ],
                'headers' => [
                    'Accept' => ['application/pdf', 'application/json'],
                ],
            ])
            ->willReturn(new Ok(new \Printgraph\PhpSdk\Client\Response\SuccessResponse(new Response(200, [], 'pdf-content'))))
        ;

        $generator = new Generator($mockClient);
        $result = $generator->generate(new GenerateRequest(
            'template',
            ['param1' => 'value1', 'param2' => 'value2']
        ));

        self::assertInstanceOf(Ok::class, $result);

        /** @var \Printgraph\PhpSdk\Client\Response\SuccessResponse $response */
        $response = $result->unwrap();
        self::assertInstanceOf(\Printgraph\PhpSdk\Client\Response\SuccessResponse::class, $response);
        self::assertEquals('pdf-content', $response->getContents());
    }

    public function testGeneratorRequestFailure(): void
    {
        $mockClient = $this->createMock(ClientInterface::class);
        $mockClient
            ->expects($this->once())
            ->method('request')
            ->with('POST', 'v1/pdf/generate', [
                'json' => [
                    'templateKey' => 'template',
                    'params' => ['param1' => 'value1', 'param2' => 'value2'],
                ],
                'headers' => [
                    'Accept' => ['application/pdf', 'application/json'],
                ],
            ])
            ->willReturn(new Err(new \Printgraph\PhpSdk\Client\Response\ErrorResponse(new Response(500, [], 'error'))))
        ;

        $generator = new Generator($mockClient);
        $result = $generator->generate(new GenerateRequest(
            'template',
            ['param1' => 'value1', 'param2' => 'value2']
        ));

        self::assertInstanceOf(Err::class, $result);

        /** @var \Printgraph\PhpSdk\Client\Response\ErrorResponse $error */
        $error = $result->unwrapErr();
        self::assertInstanceOf(\Printgraph\PhpSdk\Client\Response\ErrorResponse::class, $error);
        self::assertEquals(500, $error->getStatusCode());
        self::assertTrue($error->isServerError());
        self::assertFalse($error->isClientError());
    }
}
