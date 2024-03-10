<?php

namespace protocol\websocket;

use Exception;
use PHPUnit\Framework\TestCase;
use Surreal\SurrealWebsocket;

class ConnectionTest extends TestCase
{
    private static SurrealWebsocket $db;

    /**
     * @throws Exception
     */
    public static function setUpBeforeClass(): void
    {
        self::$db = new SurrealWebsocket(
            host: "ws://localhost:8000/rpc",
            target: ["namespace" => "test", "database" => "test"]
        );

        parent::setUpBeforeClass();
    }

    public function testConnection(): void
    {
        $connected = self::$db->isConnected();
        $this->assertTrue($connected, "The websocket is not connected");
    }

    public function testTimeout(): void
    {
        self::$db->setTimeout(10);
        $this->assertEquals(10, self::$db->getTimeout(), "The timeout is not set correctly");

        self::$db->setTimeout(5);
        $this->assertEquals(5, self::$db->getTimeout(), "The timeout is not set correctly");

        self::$db->setTimeout(0);
        $this->assertEquals(0, self::$db->getTimeout(), "The timeout is not set correctly");
    }

    /**
     * @throws Exception
     */
    public function testUse(): void
    {
        self::$db->use(["namespace" => "test", "database" => "test"]);

        $this->assertEquals("test", self::$db->getNamespace(), "The namespace is not set correctly");
        $this->assertEquals("test", self::$db->getDatabase(), "The database is not set correctly");
    }

    public function testClose(): void
    {
        self::$db->close();
        $this->assertFalse(self::$db->isConnected(), "The websocket is still connected");
    }
}