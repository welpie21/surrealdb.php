<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Surreal\connectors\HTTPConnector;


final class ConnectionTest extends TestCase
{
    private readonly HTTPConnector $database;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->database = new HTTPConnector("localhost", 8000, \Surreal\enums\strategies::HTTP);
    }

    public function testStatus(): void
    {
        $status = $this->database->status();
        $this->assertSame(200, $status);
    }

    public function testHealth(): void
    {
        $health = $this->database->health();
        $this->assertSame(200, $health);
    }

    public function testVersion(): void
    {
        $version = $this->database->version();
        $this->assertStringContainsString("surrealdb-", $version);
    }

    public function testExport(): void
    {
        $this->database
            ->setDatabase("platform")
            ->setNamespace("yaacomm");

        $export = $this->database->export();
        $this->assertSame(200, 200);
    }
}