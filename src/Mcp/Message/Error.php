<?php

declare(strict_types=1);

namespace App\Mcp\Message;

final class Error implements \JsonSerializable
{
    public string|int $id;
    public int $code = -32601;
    public string $message = 'An error occurred';

    public function jsonSerialize(): array
    {
        return [
            'jsonrpc' => '2.0',
            'id' => $this->id ?? 0,
            'error' => [
                'code' => $this->code,
                'message' => $this->message,
            ],
        ];
    }
}
