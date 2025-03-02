<?php

declare(strict_types=1);

namespace App\Mcp\Server\MethodHandler;

use App\Mcp\Message\Notification;
use App\Mcp\Message\Request;
use App\Mcp\Message\Response;

final class PingHandler extends RequestHandler
{
    public function createResponse(Request|Notification $message): Response
    {
        return new Response($message->id, []);
    }

    protected function supportedMethod(): string
    {
        return 'ping';
    }
}
