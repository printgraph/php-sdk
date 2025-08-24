# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## コマンド

### テスト実行
```bash
# 全てのテスト（CS-Fixer, PHPStan, PHPUnit）を実行
composer tests

# PHPUnitのみ実行
./vendor/bin/phpunit

# 特定のテストファイルを実行
./vendor/bin/phpunit tests/Client/ClientTest.php

# 特定のテストメソッドを実行
./vendor/bin/phpunit --filter testRequestSuccess
```

### コード品質チェック
```bash
# PHPStanで静的解析（レベル9）
./vendor/bin/phpstan analyse

# PHP CS-Fixerでコードスタイルチェック（ドライラン）
./vendor/bin/php-cs-fixer fix --dry-run

# コードスタイル自動修正
composer cs-fix
# または
./vendor/bin/php-cs-fixer fix
```

## アーキテクチャ

### コアコンポーネント
- **`Printgraph`**: メインのエントリーポイント。API操作のファサードとして機能
- **`Client\Client`**: GuzzleHttpをラップし、Result型（Ok/Err）でレスポンスを返すHTTPクライアント
- **`Api\Pdf\Generator`**: PDF生成APIのエンドポイント実装

### 依存関係
- **prewk/result**: Result型（Ok/Err）によるエラーハンドリング
- **guzzlehttp/guzzle**: HTTPクライアント

### コーディング規約
- PHP 8.1以上の機能を活用（readonly, constructor property promotion）
- 全ファイルで `declare(strict_types=1)` を使用
- PHP CS-Fixerルール: `@PER-CS`, `@PHP82Migration`
- PHPStanレベル9での厳格な型チェック