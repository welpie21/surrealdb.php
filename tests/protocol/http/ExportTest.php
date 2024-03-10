<?php

namespace protocol\http;

use PHPUnit\Framework\TestCase;
use Surreal\SurrealHTTP;

class ExportTest extends TestCase
{
    private static SurrealHTTP $db;

    public static function setUpBeforeClass(): void
    {
        self::$db = new SurrealHTTP(
            host: "http://localhost:8000",
            target: ["namespace" => "test", "database" => "test"]
        );

        parent::setUpBeforeClass(); // TODO: Change the autogenerated stub
    }

    /**
     * @throws Exception
     */
    public function testExport(): void
    {
        $result = self::$db->export("root", "root");
        $this->assertIsString($result);
    }

    public static function tearDownAfterClass(): void
    {
        self::$db->close();
        parent::tearDownAfterClass();
    }
}