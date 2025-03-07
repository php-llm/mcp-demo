<?php

declare(strict_types=1);

namespace App\Mcp\Server\Transport\Sse;

use Symfony\Component\Uid\Uuid;

interface Store
{
    public function push(Uuid $id, string $message): void;

    public function pop(Uuid $id): ?string;

    public function remove(Uuid $id): void;
}
