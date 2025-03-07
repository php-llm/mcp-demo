<?php

declare(strict_types=1);

namespace App\Mcp\Server\RequestHandler;

use App\Mcp\Message\Error;
use App\Mcp\Message\Notification;
use App\Mcp\Message\Request;
use App\Mcp\Message\Response;
use PhpLlm\LlmChain\Chain\ToolBox\ToolBoxInterface;
use PhpLlm\LlmChain\Exception\ExceptionInterface;
use PhpLlm\LlmChain\Model\Response\ToolCall;

final class ToolCallHandler extends BaseRequestHandler
{
    public function __construct(
        private readonly ToolBoxInterface $toolBox,
    ) {
    }

    public function createResponse(Request|Notification $message): Response|Error
    {
        $name = $message->params['name'];
        $arguments = $message->params['arguments'] ?? [];

        try {
            $result = $this->toolBox->execute(new ToolCall(uniqid(), $name, $arguments));
        } catch (ExceptionInterface) {
            return Error::internalError($message->id, 'Error while executing tool');
        }

        return new Response($message->id, [
            'content' => [
                ['type' => 'text', 'text' => $result],
            ],
        ]);
    }

    protected function supportedMethod(): string
    {
        return 'tools/call';
    }
}
