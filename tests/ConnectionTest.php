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
            host: "http://127.0.0.1:8000",
            namespace: "test",
            database: "test",
        );

        $this->db
            ->setAuthNamespace("test")
            ->setAuthDatabase("test")
            ->signup([
                "user" => "root",
                "pass" => "root"
            ]);

//        $this->db
//            ->setNamespace("test")
//            ->setDatabase("test")
//            ->setScope("test")
//
//        $this->db->signup([
//            "user" => "root",
//            "pass" => "root"
//        ]);
//
//        $this->db->signin();
//
//        $this->db->create("test", [
//            "name" => "test",
//            "age" => 20
//        ]);

        parent::__construct($name);
    }

    public function testConnection(): void
    {
        $this->assertEquals(200, $this->db->status());


//        print_r([
//            $this->db->status(),
//            $this->db->health(),
//            $this->db->version()
//        ]);
    }

    public function testVersion(): void
    {
        $this->assertStringStartsWith("surrealdb-", $this->db->version());
    }
}