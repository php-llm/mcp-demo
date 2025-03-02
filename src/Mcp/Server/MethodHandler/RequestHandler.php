<?php

declare(strict_types=1);

namespace App\Mcp\Server\MethodHandler;

use App\Mcp\Message\Notification;
use App\Mcp\Message\Request;

abstract class RequestHandler implements MessageHandler
{
    public function supports(Request|Notification $message): bool
    {
        return $message instanceof Request && $message->method === $this->supportedMethod();
    }

    abstract protected function supportedMethod(): string;
}
