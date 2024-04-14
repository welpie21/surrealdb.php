<?php

namespace protocol\websocket;

use Exception;
use PHPUnit\Framework\TestCase;
use Surreal\Core\Client\SurrealWebsocket;
use Surreal\Exceptions\SurrealException;

class AuthTest extends TestCase
{
    private static SurrealWebsocket $db;

    /**
     * @throws Exception
     */
    public static function setUpBeforeClass(): void
    {
        self::$db = new SurrealWebsocket(
            host: "ws://127.0.0.1:8000/rpc",
            target: ["namespace" => "test", "database" => "test"]
        );

        self::assertTrue(self::$db->isConnected());

        $token = self::$db->signin([
            "user" => "root",
            "pass" => "root"
        ]);

        self::assertIsString($token);
        self::$db->authenticate($token);

        parent::setUpBeforeClass();
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testInfo(): void
    {
        try {
            self::$db->info();
        } catch (SurrealException $exception) {
            $this->assertInstanceOf(SurrealException::class, $exception);
        }

        $token = self::$db->signup([
            "email" => "mario2",
            "pass" => "supermario",
            "ns" => "test",
            "db" => "test",
            "sc" => "account"
        ]);

        $this->assertIsString($token, "The token is not a string");

        $token = self::$db->signin([
            "email" => "mario2",
            "pass" => "supermario",
            "ns" => "test",
            "db" => "test",
            "sc" => "account"
        ]);

        $this->assertIsString($token, "The token is not a string");
    }

    /**
     * @throws Exception
     */
    public function testScopeAuth(): void
    {
        $token = self::$db->signup([
            "email" => "mario",
            "pass" => "supermario",
            "ns" => "test",
            "db" => "test",
            "sc" => "account"
        ]);

        self::assertIsString($token);

        $token = self::$db->signin([
            "email" => "mario",
            "pass" => "supermario",
            "ns" => "test",
            "db" => "test",
            "sc" => "account"
        ]);

        self::assertIsString($token);
    }

    /**
     * @throws Exception
     */
    public function testAuthenticate(): void
    {
        $token = self::$db->signin(["user" => "root", "pass" => "root"]);
        $this->assertIsString($token);

        $result = self::$db->authenticate($token);
        self::assertNull($result, "The result is not null");
    }

    /**
     * @throws Exception
     */
    public function testInvalidate(): void
    {
        $token = self::$db->signin(["user" => "root", "pass" => "root"]);
        $this->assertIsString($token);

        $info = self::$db->info();
        $this->assertNotNull($info);

        $result = self::$db->invalidate();
        $this->assertNull($result);

        try {
            self::$db->info();
        } catch (SurrealException $exception) {
            $this->assertInstanceOf(SurrealException::class, $exception);
        }
    }

    public static function tearDownAfterClass(): void
    {
        self::$db->close();

        $connected = self::$db->isConnected();
        self::assertFalse($connected);

        parent::tearDownAfterClass(); // TODO: Change the autogenerated stub
    }
}