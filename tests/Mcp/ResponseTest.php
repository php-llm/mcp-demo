<?php

declare(strict_types=1);

namespace App\Tests\Mcp;


use App\Mcp\Message\Response;
use PHPUnit\Framework\TestCase;

final class ResponseTest extends TestCase
{
    public function testWithoutData(): void
    {
        $response = new Response();
        $expected = [
            'jsonrpc' => '2.0',
            'id' => 0,
            'result' => [],
        ];

        self::assertSame($expected, $response->jsonSerialize());
    }

    public function testWithDataIntegerId(): void
    {
        $response = new Response();
        $response->id = 1;
        $response->result = ['foo' => 'bar'];
        $expected = [
            'jsonrpc' => '2.0',
            'id' => 1,
            'result' => ['foo' => 'bar'],
        ];

        self::assertSame($expected, $response->jsonSerialize());
    }

    public function testWithDataStringId(): void
    {
        $response = new Response();
        $response->id = 'abc';
        $response->result = ['foo' => 'bar'];
        $expected = [
            'jsonrpc' => '2.0',
            'id' => 'abc',
            'result' => ['foo' => 'bar'],
        ];

        self::assertSame($expected, $response->jsonSerialize());
    }
}
