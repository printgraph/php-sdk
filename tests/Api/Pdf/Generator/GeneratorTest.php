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
            ->with('POST', 'pdf/generate', [
                'json' => [
                    'templateKey' => 'template',
                    'params' => ['param1' => 'value1', 'param2' => 'value2'],
                ],
                'headers' => [
                    'Accept' => ['application/pdf', 'application/json'],
                ],
            ])
            ->willReturn(new Ok(new Response(200, [], 'pdf-content')))
        ;

        $generator = new Generator($mockClient);
        $result = $generator->generate(new GenerateRequest(
            'template',
            ['param1' => 'value1', 'param2' => 'value2']
        ));

        self::assertInstanceOf(Ok::class, $result);

        /** @var ResponseInterface $response */
        $response = $result->unwrap();
        self::assertEquals('pdf-content', $response->getBody()->getContents());
    }

    public function testGeneratorRequestFailure(): void
    {
        $mockClient = $this->createMock(ClientInterface::class);
        $mockClient
            ->expects($this->once())
            ->method('request')
            ->with('POST', 'pdf/generate', [
                'json' => [
                    'templateKey' => 'template',
                    'params' => ['param1' => 'value1', 'param2' => 'value2'],
                ],
                'headers' => [
                    'Accept' => ['application/pdf', 'application/json'],
                ],
            ])
            ->willReturn(new Err(new Response(500, [], 'error')))
        ;

        $generator = new Generator($mockClient);
        $result = $generator->generate(new GenerateRequest(
            'template',
            ['param1' => 'value1', 'param2' => 'value2']
        ));

        self::assertInstanceOf(Err::class, $result);
    }
}
