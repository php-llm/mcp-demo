<?php

declare(strict_types=1);

namespace App\Mcp\Server\NotificationHandler;

use App\Mcp\Message\Notification;

final class InitializedHandler extends BaseNotificationHandler
{
    protected function supportedNotification(): string
    {
        return 'initialized';
    }

    public function handle(Notification $notification): null
    {
        return null;
    }
}
