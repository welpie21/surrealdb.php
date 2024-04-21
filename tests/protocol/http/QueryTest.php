<?php

namespace protocol\http;

use Exception;
use PHPUnit\Framework\TestCase;
use Surreal\Cbor\Types\RecordId;
use Surreal\Core\Client\SurrealHTTP;

class QueryTest extends TestCase
{
    private static SurrealHTTP $db;

    public static function setUpBeforeClass(): void
    {
        self::$db = new SurrealHTTP(
            host: "http://localhost:8000",
            target: [
                "namespace" => "test",
                "database" => "test"
            ]
        );

        $token = self::$db->signin([
            "user" => "root",
            "pass" => "root"
        ]);

        self::assertIsString($token, "Token is not a string");
        self::$db->auth->setToken($token);

        parent::setUpBeforeClass(); // TODO: Change the autogenerated stub
    }

    /**
     * @throws Exception
     */
    public function testCrudActions(): void
    {
        self::$db->create("person:julian", ["name" => "Julian", "age" => 24]);

        $response = self::$db->create("person:beau", ["name" => "Beau", "age" => 18]);
        $this->assertIsArray($response);
        $this->assertInstanceOf(RecordId::class, $response["id"]);

        $response = self::$db->update("person:beau", ["age" => 19]);
        $this->assertIsArray($response);
        $this->assertInstanceOf(RecordId::class, $response["id"]);

        $response = self::$db->merge("person:beau", ["name" => "Beau", "age" => 25]);
        $this->assertIsArray($response);
        $this->assertInstanceOf(RecordId::class, $response["id"]);
        $this->assertArrayHasKey("name", $response);
        $this->assertArrayHasKey("age", $response);

        $response = self::$db->query("SELECT * FROM person WHERE age >= 18");
        $this->assertIsArray($response);

//        $response = self::$db->delete("person:beau");
//        $this->assertIsArray($response);
//        $this->assertInstanceOf(RecordId::class, $response["id"]);
//
        $response = self::$db->select("person:beau");
//        $this->assertEmpty($response);
    }

    /**
     * @throws Exception
     */
    public static function tearDownAfterClass(): void
    {
        self::$db->close();
        parent::tearDownAfterClass();
    }
}