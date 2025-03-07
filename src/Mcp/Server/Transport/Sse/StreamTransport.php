<?php

declare(strict_types=1);

namespace App\Mcp\Server\Transport\Sse;

use App\Mcp\Server\Transport;
use Symfony\Component\Uid\Uuid;

final readonly class StreamTransport implements Transport
{
    public function __construct(
        private string $messageEndpoint,
        private Store $store,
        private Uuid $id,
    ) {
    }

    public function initialize(): void
    {
        ignore_user_abort(true);
        $this->flushEvent('endpoint', $this->messageEndpoint);
    }

    public function isConnected(): bool
    {
        return 0 === connection_aborted();
    }

    public function receive(): \Generator
    {
        yield $this->store->pop($this->id);
    }

    public function send(string $data): void
    {
        $this->flushEvent('message', $data);
    }

    public function close(): void
    {
        $this->store->remove($this->id);
    }

    private function flushEvent(string $event, string $data): void
    {
        echo sprintf('event: %s', $event).PHP_EOL;
        echo sprintf('data: %s', $data).PHP_EOL;
        echo PHP_EOL;
        ob_flush();
        flush();
    }
}
