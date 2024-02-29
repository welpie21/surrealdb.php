<?php

use PHPUnit\Framework\TestCase;
use Surreal\SurrealHTTP;

class QueryTest extends TestCase
{
    private static SurrealHTTP $http_db;

    public static function setUpBeforeClass(): void
    {
        self::$http_db = new SurrealHTTP(
            host: "http://localhost:8000",
            target: ["namespace" => "test", "database" => "test"]
        );

        $token = self::$http_db->signin([
            "user" => "beaurt",
            "pass" => "123456"
        ]);

        self::assertIsString($token, "Token is not a string");

        self::$http_db->setToken($token);

        parent::setUpBeforeClass(); // TODO: Change the autogenerated stub
    }

    /**
     * @throws Exception
     */
    public function testCreation(): void
    {
        $response = self::$http_db->create("test", [
            "surreal" => "is",
            "the" => "best",
            "database" => [
                "ever" => "created"
            ],
            "we-are" => 1
        ]);

        $this->assertIsObject($response);
    }

    /**
     * @throws Exception
     */
    public function testUpdate(): void
    {
        $response = self::$http_db->update("person:beau", [
            "age" => 26
        ]);

        $this->assertIsObject($response);
    }

    /**
     * @throws Exception
     */
    public function testMerge(): void
    {
        $response = self::$http_db->merge("person:beau", [
            "name" => "Beau",
            "age" => 30
        ]);

        $this->assertIsObject($response);
    }

    /**
     * @throws Exception
     */
    public function testDelete(): void
    {
        $response = self::$http_db->delete("person:julian");
        $this->assertIsObject($response);
    }

    /**
     * @throws Exception
     */
    public function testSQL(): void
    {
        $response = self::$http_db->sql("SELECT * FROM person WHERE age >= 18");
        $this->assertIsArray($response);
    }

    public static function tearDownAfterClass(): void
    {
        self::$http_db->close();
        parent::tearDownAfterClass();
    }
}