<?php

declare(strict_types=1);

namespace Printgraph\PhpSdk\Tests\Api\Pdf\Generator;

use PHPUnit\Framework\TestCase;
use Printgraph\PhpSdk\Api\Pdf\Generator\GenerateRequest;
use Printgraph\PhpSdk\Api\Pdf\Generator\ValidationError;

final class GenerateRequestTest extends TestCase
{
    public function testConstructorSuccess(): void
    {
        $request = new GenerateRequest(
            'template-123',
            ['key' => 'value'],
            'A4'
        );

        $this->assertSame('template-123', $request->templateId);
        $this->assertSame(['key' => 'value'], $request->params);
        $this->assertSame('A4', $request->format);
    }

    public function testConstructorWithDefaultParams(): void
    {
        $request = new GenerateRequest('template-123');

        $this->assertSame('template-123', $request->templateId);
        $this->assertSame([], $request->params);
        $this->assertSame('A4', $request->format);
    }

    public function testConstructorWithDifferentFormat(): void
    {
        $request = new GenerateRequest('template-123', [], 'A3');

        $this->assertSame('A3', $request->format);
    }

    public function testValidateReturnsOkWhenValid(): void
    {
        $request = new GenerateRequest('template-123', [], 'A4');
        $result = $request->validate();

        $this->assertTrue($result->isOk());
        $this->assertTrue($result->unwrap());
    }

    public function testValidateReturnsErrWhenTemplateIdIsEmpty(): void
    {
        $request = new GenerateRequest('');
        $result = $request->validate();

        $this->assertTrue($result->isErr());

        /** @var ValidationError $error */
        $error = $result->unwrapErr();
        $this->assertInstanceOf(ValidationError::class, $error);
        $this->assertTrue($error->hasError('templateId'));
        $this->assertSame('templateId is required', $error->getError('templateId'));
    }

    public function testValidateReturnsErrWhenFormatIsInvalid(): void
    {
        $request = new GenerateRequest('template-123', [], 'InvalidFormat');
        $result = $request->validate();

        $this->assertTrue($result->isErr());

        /** @var ValidationError $error */
        $error = $result->unwrapErr();
        $this->assertInstanceOf(ValidationError::class, $error);
        $this->assertTrue($error->hasError('format'));
        $formatError = $error->getError('format');
        $this->assertNotNull($formatError);
        $this->assertStringContainsString('format must be one of:', $formatError);
    }

    public function testValidateReturnsMultipleErrors(): void
    {
        $request = new GenerateRequest('', [], 'InvalidFormat');
        $result = $request->validate();

        $this->assertTrue($result->isErr());

        /** @var ValidationError $error */
        $error = $result->unwrapErr();
        $this->assertInstanceOf(ValidationError::class, $error);
        $this->assertTrue($error->hasError('templateId'));
        $this->assertTrue($error->hasError('format'));
        $this->assertCount(2, $error->getErrors());
    }

    /**
     * @dataProvider validFormatProvider
     */
    public function testConstructorAcceptsAllValidFormats(string $format): void
    {
        $request = new GenerateRequest('template-123', [], $format);

        $this->assertSame($format, $request->format);
    }

    /**
     * @return array<string, array<string>>
     */
    public static function validFormatProvider(): array
    {
        return [
            'A0' => ['A0'],
            'A1' => ['A1'],
            'A2' => ['A2'],
            'A3' => ['A3'],
            'A4' => ['A4'],
            'A5' => ['A5'],
            'A6' => ['A6'],
            'Letter' => ['Letter'],
            'Legal' => ['Legal'],
            'Tabloid' => ['Tabloid'],
        ];
    }
}
