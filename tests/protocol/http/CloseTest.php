<?php

namespace protocol\http;

use PHPUnit\Framework\TestCase;

class CloseTest extends TestCase
{
    public function testClose(): void
    {
        $db = new \Surreal\SurrealHTTP(
            host: "http://localhost:8000",
            target: ["namespace" => "test", "database" => "test"]
        );

        $db->close();

        try {
            $db->close();
        }
        catch (\RuntimeException $e) {
            $this->assertEquals("The database connection is already closed.", $e->getMessage());
            $this->assertInstanceOf(\RuntimeException::class, $e);
        }

        try {
            $db->sql("SELECT * FROM person");
        } catch (\RuntimeException $e) {
            $this->assertEquals("The curl client is not initialized.", $e->getMessage());
            $this->assertInstanceOf(\RuntimeException::class, $e);
        } catch (\Surreal\classes\exceptions\SurrealException $e) {
        } catch (Exception $e) {
        }
    }
}