<?php

declare(strict_types=1);

namespace App\Controller;

use App\Mcp\Server;
use App\Mcp\Server\Transport\Sse\Store\CachePoolStore;
use App\Mcp\Server\Transport\Sse\StreamTransport;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Uid\Uuid;

#[AsController]
#[Route('/mcp', name: 'mcp_')]
final readonly class McpController
{
    public function __construct(
        private Server $server,
        private CachePoolStore $store,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    #[Route('/sse', name: 'sse', methods: ['GET'])]
    public function sse(): StreamedResponse
    {
        $id = Uuid::v4();
        $endpoint = $this->urlGenerator->generate('mcp_messages', ['id' => $id], UrlGeneratorInterface::ABSOLUTE_URL);
        $transport = new StreamTransport($endpoint, $this->store, $id);

        return new StreamedResponse(fn () => $this->server->connect($transport), headers: [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    #[Route('/messages/{id}', name: 'messages', methods: ['POST'])]
    public function messages(Request $request, Uuid $id): Response
    {
        $this->store->push($id, $request->getContent());

        return new Response();
    }
}
