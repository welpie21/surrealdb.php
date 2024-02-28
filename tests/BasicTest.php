<?php

use PHPUnit\Framework\TestCase;
use Surreal\classes\exceptions\SurrealException;
use Surreal\SurrealHTTP;

final class BasicTest extends TestCase
{
    /**
     * @throws SurrealException
     * @throws JsonException
     */
    public function testStatus(): void
    {
        $db = new SurrealHTTP(
            host: "http://localhost:8000",
            target: ["namespace" => "test", "database" => "test"]
        );

        $this->assertInstanceOf(SurrealHTTP::class, $db);

        $status = $db->status();

        $this->assertIsInt($status);
        $this->assertEquals(200, $status);

        $db->close();
    }

    /**
     * @throws SurrealException
     * @throws JsonException
     */
    public function testHealth(): void
    {
        $db = new SurrealHTTP(
            host: "http://localhost:8000",
            target: ["namespace" => "test", "database" => "test"]
        );

        $this->assertInstanceOf(SurrealHTTP::class, $db);

        $health = $db->health();

        $this->assertIsInt($health);
        $this->assertEquals(200, $health);

        $db->close();
    }

    /**
     * @throws Exception
     */
    public function testVersion(): void
    {
        $db = new SurrealHTTP(
            host: "http://localhost:8000",
            target: ["namespace" => "test", "database" => "test"]
        );

        $this->assertInstanceOf(SurrealHTTP::class, $db);

        $version = $db->version();

        $this->assertIsString($version);
        $this->assertStringStartsWith("surrealdb-", $version);

        $db->close();
    }
}