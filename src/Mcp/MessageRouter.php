<?php

declare(strict_types=1);

namespace App\Mcp;

use App\Mcp\Message\Error;
use App\Mcp\Message\Response;
use App\Mcp\MethodHandler\MethodHandler;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Traversable;

final class MessageRouter
{
    /**
     * @var array<int, MethodHandler>
     */
    private array $handlers;

    /**
     * @param iterable<MethodHandler> $tools
     */
    public function __construct(
        #[AutowireIterator('app.method_handler')]
        iterable $handlers,
        private LoggerInterface $logger,
    ) {
        $this->handlers = $handlers instanceof Traversable ? iterator_to_array($handlers) : $handlers;
    }

    public function route(string $payload): string
    {
        $this->logger->info('Received payload', ['payload' => $payload]);

        $message = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);
        $id = $message['id'] ?? 0;
        $method = $message['method'] ?? '';
        $params = $message['params'] ?? [];

        $this->logger->info('Decoded message', ['method' => $method, 'params' => $params]);

        if ('notifications/initialized' === $method) {
            return '';
        }

        try {
            $result = $this->getResult($method, $params);

            $response = new Response();
            $response->id = $id;
            $response->result = $result;
        } catch (\RuntimeException $exception) {
            $response = new Error();
            $response->id = $id;
            $response->message = $exception->getMessage();
        }

        $this->logger->info('Created response', ['response' => $response]);

        if (isset($response->result) && [] === $response->result) {
            return json_encode($response, JSON_THROW_ON_ERROR | JSON_FORCE_OBJECT);
        }

        return json_encode($response, JSON_THROW_ON_ERROR);
    }

    private function getResult(string $method, array $params): array
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($method)) {
                return $handler->getResult($params);
            }
        }

        throw new \RuntimeException(sprintf('Method "%s" not found', $method));
    }
}
