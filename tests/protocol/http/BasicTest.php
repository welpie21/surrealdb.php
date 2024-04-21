<?php

namespace protocol\http;

use Exception;
use PHPUnit\Framework\TestCase;
use Surreal\Core\Client\SurrealHTTP;

final class BasicTest extends TestCase
{
    private static SurrealHTTP $db;

    public static function setUpBeforeClass(): void
    {
        self::$db = new SurrealHTTP(
            host: "http://localhost:8000",
            target: ["namespace" => "test", "database" => "test"]
        );

        self::assertEquals("http://localhost:8000", self::$db->getHost());

        parent::setUpBeforeClass(); // TODO: Change the autogenerated stub
    }

    /**
     * @throws Exception
     */
    public function testStatus(): void
    {
        $status = self::$db->status();

        $this->assertIsInt($status);
        $this->assertEquals(200, $status);
    }

    public function testHealth(): void
    {
        $health = self::$db->health();

        $this->assertIsInt($health);
        $this->assertEquals(200, $health);
    }

    /**
     * @throws Exception
     */
    public function testVersion(): void
    {
        $version = self::$db->version();

        $this->assertIsString($version);
        $this->assertStringStartsWith("surrealdb-", $version);
    }

    public function testToken(): void
    {
        self::$db->auth->setToken("sometoken");
        $token = self::$db->auth->getToken();

        $this->assertEquals("sometoken", $token);

        self::$db->auth->setToken(null);
        $this->assertNull(self::$db->auth->getToken());
    }

    public static function tearDownAfterClass(): void
    {
        self::$db->close();
        parent::tearDownAfterClass();
    }
}