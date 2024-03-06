<?php

namespace protocol\websocket;

use PHPUnit\Framework\TestCase;
use Surreal\SurrealWebsocket;

class QueryTest extends TestCase
{
    private static SurrealWebsocket $db;

    public static function setUpBeforeClass(): void
    {
        self::$db = new SurrealWebsocket(
            host: "ws://localhost:8000/rpc",
            target: ["namespace" => "test", "database" => "test"]
        );

        self::assertTrue(self::$db->isConnected());

        parent::setUpBeforeClass();
    }

    public static function tearDownAfterClass(): void
    {
        self::$db->close();
        self::assertFalse(self::$db->isConnected());

        parent::tearDownAfterClass();
    }
}