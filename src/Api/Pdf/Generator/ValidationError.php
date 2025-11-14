<?php

declare(strict_types=1);

namespace Printgraph\PhpSdk\Api\Pdf\Generator;

/**
 * バリデーションエラーを表す値オブジェクト
 *
 * @phpstan-immutable
 */
final class ValidationError
{
    /**
     * @param array<string, string> $errors フィールド名とエラーメッセージのマップ
     */
    public function __construct(
        private readonly array $errors,
    ) {}

    /**
     * 単一のエラーを持つValidationErrorを作成
     */
    public static function single(string $field, string $message): self
    {
        return new self([$field => $message]);
    }

    /**
     * @return array<string, string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * 特定のフィールドのエラーメッセージを取得
     */
    public function getError(string $field): ?string
    {
        return $this->errors[$field] ?? null;
    }

    /**
     * 全てのエラーメッセージを結合した文字列を取得
     */
    public function getMessage(): string
    {
        return implode(', ', $this->errors);
    }

    /**
     * エラーが存在するかチェック
     */
    public function hasError(string $field): bool
    {
        return isset($this->errors[$field]);
    }
}
