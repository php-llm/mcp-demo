<?php

declare(strict_types=1);

namespace App\Tests\Mcp;

use App\Mcp\Message\Error;
use PHPUnit\Framework\TestCase;

final class ErrorTest extends TestCase
{
    public function testWithoutData(): void
    {
        $error = new Error();
        $expected = [
            'jsonrpc' => '2.0',
            'id' => 0,
            'error' => [
                'code' => -32601,
                'message' => 'An error occurred',
            ],
        ];

        self::assertSame($expected, $error->jsonSerialize());
    }

    public function testWithDataIntegerId(): void
    {
        $error = new Error();
        $error->id = 1;
        $error->code = -32602;
        $error->message = 'Another error occurred';
        $expected = [
            'jsonrpc' => '2.0',
            'id' => 1,
            'error' => [
                'code' => -32602,
                'message' => 'Another error occurred',
            ],
        ];

        self::assertSame($expected, $error->jsonSerialize());
    }
}
