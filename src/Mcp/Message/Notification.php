<?php

declare(strict_types=1);

namespace App\Mcp\Message;

final readonly class Notification implements \JsonSerializable
{
    /**
     * @param array<string, mixed>|null $params
     */
    public function __construct(
        public string $method,
        public ?array $params = null,
    ) {
    }

    /**
     * @param array{method: string, params?: array<string, mixed>} $data
     */
    public static function from(array $data): self
    {
        return new self(
            $data['method'],
            $data['params'] ?? null,
        );
    }

    /**
     * @return array{jsonrpc: string, method: string, params: array<string, mixed>|null}
     */
    public function jsonSerialize(): array
    {
        return [
            'jsonrpc' => '2.0',
            'method' => $this->method,
            'params' => $this->params,
        ];
    }
}
