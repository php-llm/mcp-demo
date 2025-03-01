<?php

declare(strict_types=1);

namespace App\Mcp\MethodHandler;

final class InitializeHandler implements MethodHandler
{
    public function supports(string $method): bool
    {
        return 'initialize' === $method;
    }

    public function getResult(array $parameter): array
    {
        return [
            'protocolVersion' => '2024-11-05',
            'capabilities' => [
                'tools' => ['listChanged' => true],
            ],
            'serverInfo' => ['name' => 'mcp-demo', 'version' => '0.0.1'],
        ];
    }
}
