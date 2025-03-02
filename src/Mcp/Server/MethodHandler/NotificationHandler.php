<?php

declare(strict_types=1);

namespace App\Mcp\Server\MethodHandler;

use App\Mcp\Message\Error;
use App\Mcp\Message\Notification;
use App\Mcp\Message\Request;
use App\Mcp\Message\Response;

abstract class NotificationHandler implements MessageHandler
{
    public function supports(Request|Notification $message): bool
    {
        return $message instanceof Notification
            && $message->method === sprintf('notifications/%s', $this->supportedNotification());
    }

    public function createResponse(Request|Notification $message): Response|Error|null
    {
        $this->execute();

        return null;
    }

    /**
     * To be implemented by the concrete handler if needed to execute logic on receiving a notification.
     */
    protected function execute(): void
    {
    }

    abstract protected function supportedNotification(): string;
}
