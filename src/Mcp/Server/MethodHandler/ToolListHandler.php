<?php

declare(strict_types=1);

namespace App\Mcp\Server\MethodHandler;

use App\Mcp\Message\Notification;
use App\Mcp\Message\Request;
use App\Mcp\Message\Response;
use PhpLlm\LlmChain\Chain\ToolBox\Metadata;
use PhpLlm\LlmChain\Chain\ToolBox\ToolBoxInterface;

final class ToolListHandler extends RequestHandler
{
    public function __construct(
        private ToolBoxInterface $toolBox,
    ) {
    }

    public function createResponse(Request|Notification $message): Response
    {
        return new Response($message->id, [
            'tools' => array_map(function (Metadata $tool) {
                return [
                    'name' => $tool->name,
                    'description' => $tool->description,
                    'inputSchema' => $tool->parameters ?? [
                        'type' => 'object',
                        '$schema' => 'http://json-schema.org/draft-07/schema#',
                    ],
                ];
            }, $this->toolBox->getMap()),
        ]);
    }

    protected function supportedMethod(): string
    {
        return 'tools/list';
    }
}
