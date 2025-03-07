<?php

declare(strict_types=1);

namespace App\Mcp\Server\RequestHandler;

use App\Mcp\Message\Request;
use App\Mcp\Server\RequestHandler;

abstract class BaseRequestHandler implements RequestHandler
{
    public function supports(Request $message): bool
    {
        return $message->method === $this->supportedMethod();
    }

    abstract protected function supportedMethod(): string;
}
