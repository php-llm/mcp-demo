<?php

declare(strict_types=1);

namespace App\Mcp\Server;

use App\Mcp\Message\Notification;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(tags: ['mcp.notification_handler'])]
interface NotificationHandler
{
    public function supports(Notification $message): bool;

    public function handle(Notification $notification): null;
}
