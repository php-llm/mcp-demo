<?php

declare(strict_types=1);

namespace App\Mcp;

use App\Mcp\Server\JsonRpcHandler;
use App\Mcp\Server\Transport\Transport;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final readonly class Server
{
    public function __construct(
        private JsonRpcHandler $jsonRpcHandler,
        private LoggerInterface $logger = new NullLogger(),
    ) {
    }

    public function connect(Transport $transport): void
    {
        $transport->initialize();
        $this->logger->info('Transport initialized');

        while ($transport->isConnected()) {
            foreach ($transport->receive() as $message) {
                $response = $this->jsonRpcHandler->process($message);

                if (null === $response) {
                    continue;
                }

                $transport->send($response);
            }

            usleep(1000);
        }

        $transport->close();
        $this->logger->info('Transport closed');
    }
}
