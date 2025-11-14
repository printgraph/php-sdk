<?php

declare(strict_types=1);

namespace Printgraph\PhpSdk\Api\Pdf\Generator;

use Prewk\Result;
use Prewk\Result\{Ok, Err};

final class GenerateRequest
{
    private const ALLOWED_FORMATS = ['A0', 'A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'Legal', 'Letter', 'Tabloid'];

    /**
     * @param mixed[] $params
     */
    public function __construct(
        public readonly string $templateId,
        public readonly array  $params = [],
        public readonly string $format = 'A4',
    ) {}

    /**
     * @return Result<bool, ValidationError>
     * @phpstan-return Result<bool, ValidationError>
     */
    public function validate(): Result
    {
        $errors = [];

        if (trim($this->templateId) === '') {
            $errors['templateId'] = 'templateId is required';
        }

        if (!in_array($this->format, self::ALLOWED_FORMATS, true)) {
            $errors['format'] = 'format must be one of: ' . implode(', ', self::ALLOWED_FORMATS);
        }

        if (!empty($errors)) {
            /** @var Result<bool, ValidationError> */
            return new Err(new ValidationError($errors));
        }

        /** @var Result<bool, ValidationError> */
        return new Ok(true);
    }
}
