<?php

declare(strict_types=1);

namespace App\Mcp\MethodHandler;

final class PingHandler implements MethodHandler
{
    public function supports(string $method): bool
    {
        return 'ping' === $method;
    }

    public function getResult(array $parameter): array
    {
        return [];
    }
}
