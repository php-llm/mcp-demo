<?php

declare(strict_types=1);

namespace App\Command;

use App\Mcp\MessageRouter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Heavily inspired by https://jolicode.com/blog/mcp-the-open-protocol-that-turns-llm-chatbots-into-intelligent-agents.
 */
#[AsCommand('mcp', 'Starts an MCP server')]
class McpCommand extends Command
{
    public function __construct(
        private readonly MessageRouter $messageRouter,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $buffer = '';

        while (true) {
            $line = fgets(STDIN);
            if (false === $line) {
                usleep(1000);

                continue;
            }
            $buffer .= $line;
            if (str_contains($buffer, "\n")) {
                $lines = explode("\n", $buffer);
                $buffer = array_pop($lines);
                foreach ($lines as $line) {
                    $output->writeln($this->messageRouter->route($line));
                }
            }
        }

        return Command::SUCCESS;
    }
}
