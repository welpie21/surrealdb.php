<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Surreal\Surreal;

final class ConnectionTest extends TestCase
{
    private Surreal $db;

    public function __construct(string $name)
    {
        $this->db = new Surreal(
            host: "http://localhost:8000",
            namespace: "test",
            database: "test",
        );

        parent::__construct($name);
    }

    public function testConnection(): void
    {
        $this->assertEquals(200, $this->db->status());

        print_r([
            $this->db->status(),
            $this->db->health(),
            $this->db->version()
        ]);
    }

    public function testVersion(): void
    {
        print_r(
            $this->db->create("test", [
                "name" => "test",
                "age" => 20
            ])
        );

        $this->assertStringStartsWith("surrealdb-", $this->db->version());
    }
}