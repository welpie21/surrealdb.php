<?php

namespace protocol\http;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Surreal\classes\exceptions\SurrealException;
use Surreal\SurrealHTTP;

class ConnectionTest extends TestCase
{
    public function testWrongConnection(): void
    {
        $db = new SurrealHTTP(
            host: "http://localhost:8001", // <-- wrong port
            target: ["namespace" => "test", "database" => "test"]
        );

        try {
            $db->sql("SELECT * FROM person");
        } catch (RuntimeException $e) {
            $this->assertStringStartsWith("Failed to connect to localhost port 8001", $e->getMessage());
            $this->assertInstanceOf(RuntimeException::class, $e);
        } catch (SurrealException $e) {
        } catch (Exception $e) {
        }
    }
}