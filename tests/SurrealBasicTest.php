<?php

use PHPUnit\Framework\TestCase;
use Surreal\Surreal;

final class SurrealBasicTest extends TestCase
{
    public function __construct(string $name)
    {
        parent::__construct($name);
    }

    /**
     * @throws Exception
     */
    public function testConnection(): void
    {
        $db = new Surreal(
            host: "http://127.0.0.1:8000",
            namespace: "test",
            database: "test"
        );

        // test status
        $status = $db->status();
        $this->assertEquals(200, $status, "Status check failed");

        // test health
        $health = $db->health();
        $this->assertEquals(200, $health, "Health check failed");

        $db->close();
    }

    /**
     * @throws Exception
     */
    public function testVersion(): void
    {
        $db = new Surreal(
            host: "http://127.0.0.1:8000",
            namespace: "test",
            database: "test"
        );

        $version = $db->version();
        $this->assertIsString($version, "Version is not a string.");
        $this->assertMatchesRegularExpression("/surrealdb-\d+\.\d+\.\d+/", $version, "Version is not in the correct format.");

        $db->close();
    }

    /**
     *
     * @throws Exception
     */
    public function testImport(): void
    {
        $db = new Surreal(
            host: "http://127.0.0.1:8000",
            namespace: "test",
            database: "test"
        );

        // test with valid credentials
        $file = file_get_contents(__DIR__ . "\\assets\\import.surql");
        $response = $db->import($file, "root", "root");

        $this->assertIsString($response, "Import response is not a string.");

        // check if one of the records exists
        $response = $db->sql("SELECT * FROM ONLY person:beau");
        $this->assertIsArray($response, "Response is not an array.");

        $this->assertArrayHasKey("id", $response, "Response does not contain a id key.");
        $this->assertArrayHasKey("age", $response, "Response does not contain a age key.");
        $this->assertArrayHasKey("name", $response, "Response does not contain a name key.");

        // test with invalid credentials
        $response = $db->import($file, "not-root", "not-root");

        $this->assertIsString($response, "Import response is not a string.");
        $this->assertEquals("There was a problem with authentication", $response, "Import response is not correct.");

        // remove data
        $db->sql("DELETE person");
        $db->sql("DELETE likes");
        $db->sql("DELETE product");

        $db->close();
    }

    /**
     * @throws Exception
     */
    public function testExport(): void
    {
        $db = new Surreal(
            host: "http://127.0.0.1:8000",
            namespace: "test",
            database: "test"
        );

        $response = $db->export("root", "root");

        $this->assertIsString($response, "Export response is not a string.");

        // TODO: improve this test

        $db->close();
    }


}