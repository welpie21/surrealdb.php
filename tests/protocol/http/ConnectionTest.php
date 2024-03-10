<?php

namespace protocol\http;

use PHPUnit\Framework\TestCase;

class ConnectionTest extends TestCase
{
    public function testConnection(): void
    {
        $db = new \Surreal\SurrealHTTP(
            host: "http://localhost:8001", // <-- wrong port
            target: ["namespace" => "test", "database" => "test"]
        );

        try {
            $db->sql("SELECT * FROM person");
        } catch (\RuntimeException $e) {
            $this->assertStringStartsWith("Failed to connect to localhost port 8001", $e->getMessage());
            $this->assertInstanceOf(\RuntimeException::class, $e);
        } catch (\Surreal\classes\exceptions\SurrealException $e) {
        } catch (Exception $e) {
        }
    }
}