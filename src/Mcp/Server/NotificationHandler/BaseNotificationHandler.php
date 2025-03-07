<?php

declare(strict_types=1);

namespace App\Mcp\Server\NotificationHandler;

use App\Mcp\Message\Notification;
use App\Mcp\Server\NotificationHandler;

abstract class BaseNotificationHandler implements NotificationHandler
{
    public function supports(Notification $message): bool
    {
        return $message->method === sprintf('notifications/%s', $this->supportedNotification());
    }

    abstract protected function supportedNotification(): string;
}
