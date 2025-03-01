<?php

declare(strict_types=1);

namespace App\Mcp\MethodHandler;

use App\Kernel;
use PhpLlm\LlmChain\Chain\ToolBox\ToolBoxInterface;
use PhpLlm\LlmChain\Model\Response\ToolCall;

final readonly class ToolCallHandler implements MethodHandler
{
    public function __construct(
        private ToolBoxInterface $toolBox,
    ) {
    }

    public function supports(string $method): bool
    {
        return 'tools/call' === $method;
    }

    public function getResult(array $parameter): array
    {
        $name = $parameter['name'];
        $arguments = $parameter['arguments'] ?? [];

        $result = $this->toolBox->execute(new ToolCall(uniqid(), $name, $arguments));

        return [
            'content' => [
                ['type' => 'text', 'text' => $result],
            ]
        ];
    }
}
