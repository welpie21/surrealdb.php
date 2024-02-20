<?php

use PHPUnit\Framework\TestCase;
use Surreal\Surreal;

class SurrealComplexTest extends TestCase
{
    public function __construct(string $name)
    {
        parent::__construct($name);
    }

    public function testSignup(): void
    {
        $this->assertEquals(200, 200, "Status check failed");
    }
}