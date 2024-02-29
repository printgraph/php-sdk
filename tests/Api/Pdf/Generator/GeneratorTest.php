<?php

namespace Printgraph\PhpSdk\Tests\Api\Pdf\Generator;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Prewk\Result\{Ok, Err};
use Printgraph\PhpSdk\Api\Pdf\Generator\GenerateRequest;
use Printgraph\PhpSdk\Api\Pdf\Generator\Generator;
use Printgraph\PhpSdk\Client\ClientInterface;

final class GeneratorTest extends TestCase
{
    public function testGeneratorRequestSuccess(): void
    {
        $mockClient = $this->createMock(ClientInterface::class);
        $mockClient
            ->expects($this->once())
            ->method('request')
            ->with('POST', 'pdf/generate', [
                'form_params' => [
                    'template' => 'template',
                    'params' => ['param1' => 'value1', 'param2' => 'value2'],
                ],
                'headers' => [
                    'Accept' => ['application/pdf', 'application/json']
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
        self::assertEquals('pdf-content', $result->unwrap()->getBody()->getContents());
    }

    public function testGeneratorRequestFailure(): void
    {
        $mockClient = $this->createMock(ClientInterface::class);
        $mockClient
            ->expects($this->once())
            ->method('request')
            ->with('POST', 'pdf/generate', [
                'form_params' => [
                    'template' => 'template',
                    'params' => ['param1' => 'value1', 'param2' => 'value2'],
                ],
                'headers' => [
                    'Accept' => ['application/pdf', 'application/json']
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