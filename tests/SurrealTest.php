<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Surreal\Surreal;

final class SurrealTest extends TestCase
{
    private Surreal $db;

    public function __construct(string $name)
    {
        $this->db = new Surreal(
            host: "http://127.0.0.1:8000",
            namespace: "test",
            database: "test",
        );

        parent::__construct($name);
    }

    public function testStatus(): void
    {
        $status = $this->db->status();

        $this->assertIsInt($status, "Status is not an integer");
        $this->assertEquals(200, $status, "Status is not 200 - Probably the database is not running");
    }

    public function testVersion(): void
    {
        $version = $this->db->version();

        $this->assertIsString($version, "Version is not a string");
        $this->assertStringStartsWith("surrealdb-", $version, "Version is unknown");
    }

    public function testHealth(): void
    {
        $health = $this->db->health();

        $this->assertIsInt($health, "Health is not a string");
        $this->assertEquals(200, $health, "Health is not 200 - Probably the database is not running");
    }

    /**
     * @throws Exception
     */
    public function testCreateRecord(): void
    {
        $record = $this->db->create("test", ["name" => "test"]);
        var_dump($record);
    }
}