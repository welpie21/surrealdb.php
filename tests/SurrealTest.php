<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Surreal\Surreal;

final class SurrealTest extends TestCase
{
    private Surreal $db;

    public function __construct(string $name)
    {
        $this->db = new Surreal(
            host: "http://127.0.0.1:8000",
            namespace: "test",
            database: "test"
        );

        parent::__construct($name);
    }
}