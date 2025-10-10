<?php

declare(strict_types=1);

namespace Printgraph\PhpSdk\Api\Pdf\Generator;

final class GenerateRequest
{
    /**
     * @param mixed[] $params
     */
    public function __construct(
        public readonly string $templateId,
        public readonly array  $params = [],
        public readonly string $format = 'A4',
    ) {}

    public function validate(): void
    {
        if (empty($this->templateId)) {
            throw new \InvalidArgumentException('templateId is required');
        }

        if (empty($this->params)) {
            throw new \InvalidArgumentException('params is required');
        }

        $allowedFormats = ['A4', 'A3', 'Letter', 'Legal', 'Tabloid', 'A0', 'A1', 'A2', 'A5', 'A6'];
        if (!in_array($this->format, $allowedFormats, true)) {
            throw new \InvalidArgumentException('format must be one of: ' . implode(', ', $allowedFormats));
        }
    }
}
