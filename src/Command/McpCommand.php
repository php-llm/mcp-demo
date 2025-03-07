<?php

declare(strict_types=1);

namespace App\Command;

use PhpLlm\McpSdk\Server;
use PhpLlm\McpSdk\Server\Transport\Stdio\SymfonyConsoleTransport;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('mcp', 'Starts an MCP server')]
class McpCommand extends Command
{
    public function __construct(
        private readonly Server $server,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->server->connect(
            new SymfonyConsoleTransport($input, $output)
        );

        return Command::SUCCESS;
    }
}
