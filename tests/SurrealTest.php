<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Surreal\Surreal;
use Surreal\SurrealAuthorization;

final class SurrealTest extends TestCase
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

        // test version
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
            database: "test",
            authorization: SurrealAuthorization::create()
                ->setAuthNamespace("test")
                ->setAuthDatabase("test")
        );

        // sign in
        $db->signin([
            "user" => "root",
            "pass" => "root"
        ]);

        $path = __DIR__ . "/assets/import.surql";
        $response = $db->import($path);

        var_dump($response);
        $db->close();
    }

    public function testDir(): void
    {
        $this->assertDirectoryExists(__DIR__);
    }
}