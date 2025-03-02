<?php

declare(strict_types=1);

namespace App\Mcp\Message;

final class Factory
{
    public function create(string $json): Request|Notification
    {
        $data = json_decode($json, true, flags: JSON_THROW_ON_ERROR);

        if (!isset($data['method'])) {
            throw new \InvalidArgumentException('Invalid JSON-RPC request, missing method');
        }

        if (str_starts_with($data['method'], 'notifications/')) {
            return Notification::from($data);
        }

        return Request::from($data);
    }
}
