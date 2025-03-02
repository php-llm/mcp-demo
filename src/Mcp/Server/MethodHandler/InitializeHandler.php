<?php

declare(strict_types=1);

namespace App\Mcp\Server\MethodHandler;

use App\Mcp\Message\Notification;
use App\Mcp\Message\Request;
use App\Mcp\Message\Response;

final class InitializeHandler extends RequestHandler
{
    public function createResponse(Request|Notification $message): Response
    {
        return new Response($message->id, [
            'protocolVersion' => '2024-11-05',
            'capabilities' => [
                'tools' => ['listChanged' => true],
            ],
            'serverInfo' => ['name' => 'mcp-demo', 'version' => '0.0.1'],
        ]);
    }

    protected function supportedMethod(): string
    {
        return 'initialize';
    }
}
