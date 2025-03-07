<?php

declare(strict_types=1);

namespace App\Mcp\Server;

use App\Mcp\Message\Error;
use App\Mcp\Message\Factory;
use App\Mcp\Message\Notification;
use App\Mcp\Message\Request;
use App\Mcp\Message\Response;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

final readonly class JsonRpcHandler
{
    /**
     * @var array<int, RequestHandler>
     */
    private array $requestHandlers;

    /**
     * @var array<int, NotificationHandler>
     */
    private array $notificationHandlers;

    /**
     * @param iterable<RequestHandler>      $requestHandlers
     * @param iterable<NotificationHandler> $notificationHandlers
     */
    public function __construct(
        private Factory $messageFactory,
        #[AutowireIterator('mcp.request_handler')]
        iterable $requestHandlers,
        #[AutowireIterator('mcp.notification_handler')]
        iterable $notificationHandlers,
        private LoggerInterface $logger,
    ) {
        $this->requestHandlers = $requestHandlers instanceof \Traversable ? iterator_to_array($requestHandlers) : $requestHandlers;
        $this->notificationHandlers = $notificationHandlers instanceof \Traversable ? iterator_to_array($notificationHandlers) : $notificationHandlers;
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
            return $message instanceof Notification
                ? $this->handleNotification($message) : $this->encodeResponse($this->handleRequest($message));
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

    private function handleNotification(Notification $notification): null
    {
        foreach ($this->notificationHandlers as $handler) {
            if ($handler->supports($notification)) {
                return $handler->handle($notification);
            }
        }

        $this->logger->warning(sprintf('No handler found for "%s".', $notification->method), ['notification' => $notification]);

        return null;
    }

    private function handleRequest(Request $request): Response|Error
    {
        foreach ($this->requestHandlers as $handler) {
            if ($handler->supports($request)) {
                return $handler->createResponse($request);
            }
        }

        throw new \InvalidArgumentException(sprintf('No handler found for method "%s".', $request->method));
    }
}
