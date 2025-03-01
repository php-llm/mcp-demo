<?php

declare(strict_types=1);

namespace App\Mcp\Message;

final class Response implements \JsonSerializable
{
    public string|int $id;
    public array $result = [];

    public function jsonSerialize(): mixed
    {
        return [
            'jsonrpc' => '2.0',
            'id' => $this->id ?? 0,
            'result' => $this->result,
        ];
    }
}
