<?php

declare(strict_types=1);

namespace App\Mcp\Message;

final class Request implements \JsonSerializable
{
    /**
     * @param array<string, mixed>|null $params
     */
    public function __construct(
        public int|string $id,
        public string $method,
        public ?array $params = null,
    ) {
    }

    /**
     * @param array{id: string|int, method: string, params?: array<string, mixed>} $data
     */
    public static function from(array $data): self
    {
        return new self(
            $data['id'],
            $data['method'],
            $data['params'] ?? null,
        );
    }

    /**
     * @return array{jsonrpc: string, id: string|int, method: string, params: array<string, mixed>|null}
     */
    public function jsonSerialize(): array
    {
        return [
            'jsonrpc' => '2.0',
            'id' => $this->id,
            'method' => $this->method,
            'params' => $this->params,
        ];
    }
}
