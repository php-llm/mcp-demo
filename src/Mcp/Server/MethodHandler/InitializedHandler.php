<?php

declare(strict_types=1);

namespace App\Mcp\Server\MethodHandler;

final class InitializedHandler extends NotificationHandler
{
    protected function supportedNotification(): string
    {
        return 'initialized';
    }
}
