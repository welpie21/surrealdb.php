<?php

class ExportTest extends \PHPUnit\Framework\TestCase
{
    public function testSome(): void
    {
        $this->assertTrue(true);
    }

    /**
     * @throws Exception
     */
    public function testExport(): void
    {
        $db = new \Surreal\SurrealHTTP(
            host: "http://localhost:8000",
            target: ["namespace" => "test", "database" => "test"]
        );

        $result = $db->export("root", "root");

        $this->assertIsString($result);

        $db->close();
    }
}