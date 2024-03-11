<?php

namespace protocol\http;

use Exception;
use PHPUnit\Framework\TestCase;
use Surreal\SurrealHTTP;

class AuthTest extends TestCase
{
    private static SurrealHTTP $db;

    /**
     * @throws Exception
     */
    public static function setUpBeforeClass(): void
    {
        self::$db = new SurrealHTTP(
            host: "http://localhost:8000",
            target: ["namespace" => "test", "database" => "test"]
        );

        $token = self::$db->signin([
            "user" => "root",
            "pass" => "root"
        ]);

        self::$db->setToken($token);

        usleep(1000000);

        parent::setUpBeforeClass(); // TODO: Change the autogenerated stub
    }

    /**
     * @throws Exception
     */
    public function testAuth(): void
    {
        $input = [
            "email" => "beau",
            "pass" => "123456",
            "ns" => "test",
            "db" => "test",
            "sc" => "http_user"
        ];

        $token = self::$db->signup($input);
        $this->assertIsString($token);

        $token = self::$db->signin($input);
        $this->assertIsString($token);
    }

    public function testInvalidate(): void
    {
        self::$db->invalidate();
        $this->assertNull(self::$db->getToken());
    }

    public static function tearDownAfterClass(): void
    {
        self::$db->close();
        parent::tearDownAfterClass(); // TODO: Change the autogenerated stub
    }
}