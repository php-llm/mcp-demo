<?php

declare(strict_types=1);

namespace App\Mcp\Server;

use App\Mcp\Message\Error;
use App\Mcp\Message\Factory;
use App\Mcp\Message\Notification;
use App\Mcp\Message\Request;
use App\Mcp\Message\Response;
use App\Mcp\Server\MethodHandler\MessageHandler;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

final readonly class JsonRpcHandler
{
    /**
     * @var array<int, MessageHandler>
     */
    private array $handlers;

    /**
     * @param iterable<MessageHandler> $handlers
     */
    public function __construct(
        private Factory $messageFactory,
        #[AutowireIterator('mcp.message_handler')]
        iterable $handlers,
        private LoggerInterface $logger,
    ) {
        $this->handlers = $handlers instanceof \Traversable ? iterator_to_array($handlers) : $handlers;
    }

    public function process(string $message): ?string
    {
        $this->logger->info('Received message to process', ['message' => $message]);

        try {
            $message = $this->messageFactory->create($message);
        } catch (\JsonException $exception) {
            $this->logger->warning('Failed to decode json message', ['exception' => $exception]);

            return $this->encodeResponse(Error::parseError($exception->getMessage()));
        } catch (\InvalidArgumentException $exception) {
            $this->logger->warning('Failed to create message', ['exception' => $exception]);

            return $this->encodeResponse(Error::invalidRequest(0, $exception->getMessage()));
        }

        $this->logger->info('Decoded incoming message', ['message' => $message]);

        try {
            return $this->encodeResponse($this->getResponse($message));
        } catch (\DomainException) {
            return null;
        } catch (\InvalidArgumentException $exception) {
            $this->logger->warning('Failed to create response', ['exception' => $exception]);

            return $this->encodeResponse(Error::methodNotFound($message->id ?? 0, $exception->getMessage()));
        }
    }

    private function encodeResponse(Response|Error|null $response): ?string
    {
        if (null === $response) {
            $this->logger->warning('Response is null');

            return null;
        }

        $this->logger->info('Encoding response', ['response' => $response]);

        if ($response instanceof Response && [] === $response->result) {
            return json_encode($response, JSON_THROW_ON_ERROR | JSON_FORCE_OBJECT);
        }

        return json_encode($response, JSON_THROW_ON_ERROR);
    }

    private function getResponse(Request|Notification $message): Response|Error
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($message)) {
                return $handler->createResponse($message) ?? throw new \DomainException('Response is null');
            }
        }

        throw new \InvalidArgumentException(sprintf('Method "%s" not found', $message->method));
    }
}
