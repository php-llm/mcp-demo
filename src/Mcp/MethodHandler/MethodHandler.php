<?php

declare(strict_types=1);

namespace App\Mcp\MethodHandler;

use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(tags: ['app.method_handler'])]
interface MethodHandler
{
    public function supports(string $method): bool;

    public function getResult(array $parameter): array;
}
