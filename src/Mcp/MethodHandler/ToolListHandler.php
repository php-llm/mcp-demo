<?php

declare(strict_types=1);

namespace App\Mcp\MethodHandler;

use PhpLlm\LlmChain\Chain\ToolBox\Metadata;
use PhpLlm\LlmChain\Chain\ToolBox\ToolBoxInterface;

final readonly class ToolListHandler implements MethodHandler
{
    public function __construct(
        private ToolBoxInterface $toolBox,
    ) {
    }

    public function supports(string $method): bool
    {
        return 'tools/list' === $method;
    }

    public function getResult(array $parameter): array
    {
        return [
            'tools' => array_map(function (Metadata $tool) {
                return [
                    'name' => $tool->name,
                    'description' => $tool->description,
                    'inputSchema' => $tool->parameters ?? [
                        'type' => 'object',
                        '$schema' => 'http://json-schema.org/draft-07/schema#',
                    ],
                ];
            }, $this->toolBox->getMap())
        ];
    }
}
