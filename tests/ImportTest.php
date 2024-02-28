<?php

class ImportTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws Exception
     */
    public function testImport(): void
    {
        $db = new \Surreal\SurrealHTTP(
            host: "http://localhost:8000",
            target: ["namespace" => "test", "database" => "test"]
        );

        $file = __DIR__ . "/assets/import.surql";
        $file = file_get_contents($file);

        $result = $db->import($file, "root", "root");

        $this->assertIsArray($result);

        $db->close();
    }
}