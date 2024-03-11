<?php

namespace protocol\websocket;

use Exception;
use PHPUnit\Framework\TestCase;
use Surreal\classes\exceptions\SurrealException;
use Surreal\SurrealWebsocket;

class BasicTest extends TestCase
{
    static private SurrealWebsocket $db;

    /**
     * @throws Exception
     */
    public static function setUpBeforeClass(): void
    {
        self::$db = new SurrealWebsocket(
            host: "ws://localhost:8000/rpc",
            target: ["namespace" => "test", "database" => "test"]
        );

        parent::setUpBeforeClass(); // TODO: Change the autogenerated stub
    }

    /**
     * @throws Exception
     */
    public function testUse(): void
    {
        $result = self::$db->use(["namespace" => "test", "database" => "test"]);
        self::assertNull($result);
    }

    /**
     * @throws Exception
     */
    public function testLet(): void
    {
        $result = self::$db->let("x", 1);
        self::assertNull($result);
    }

    /**
     * @throws Exception
     */
    public function testUnset(): void
    {
        $result = self::$db->unset("x");
        self::assertNull($result);
    }

    public function testWebsocketErrorResponse(): void
    {
        try {
            self::$db->query("SELECT * X FROM WHERE WHERE X = 1");
        } catch (SurrealException $exception) {

        }
    }

    public static function tearDownAfterClass(): void
    {
        self::$db->close();
        parent::tearDownAfterClass(); // TODO: Change the autogenerated stub
    }
}