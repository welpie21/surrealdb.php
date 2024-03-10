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

        [$table, $id] = $this->parseThing("person");

        $this->assertEquals("person", $table);
        $this->assertEquals(null, $id);
    }
}