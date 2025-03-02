<?php

declare(strict_types=1);

namespace App\Mcp\Server\MethodHandler;

use App\Mcp\Message\Error;
use App\Mcp\Message\Notification;
use App\Mcp\Message\Request;
use App\Mcp\Message\Response;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(tags: ['mcp.message_handler'])]
interface MessageHandler
{
    public function supports(Request|Notification $message): bool;

    public function createResponse(Request|Notification $message): Response|Error|null;
}
