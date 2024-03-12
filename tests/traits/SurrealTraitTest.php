<?php

namespace traits;

use PHPUnit\Framework\TestCase;
use Surreal\traits\SurrealTrait;

class SurrealTraitTest extends TestCase
{
    use SurrealTrait;

    public function testSurrealTrait()
    {
        $id = "person:beau";
        [$table, $id] = $this->parseThing($id);

        $this->assertEquals("person", $table);
        $this->assertEquals("beau", $id);

        $result = $this->parseThing("person");

        $this->assertArrayHasKey(0, $result);
        $this->assertArrayNotHasKey(1, $result);

        $this->assertEquals("person", $result[0]);
    }
}