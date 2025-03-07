<?php

declare(strict_types=1);

namespace App\Mcp\Server;

use App\Mcp\Message\Error;
use App\Mcp\Message\Request;
use App\Mcp\Message\Response;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(tags: ['mcp.request_handler'])]
interface RequestHandler
{
    public function supports(Request $message): bool;

    public function createResponse(Request $message): Response|Error;
}
