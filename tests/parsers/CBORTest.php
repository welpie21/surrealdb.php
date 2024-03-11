<?php

namespace parsers;

use PHPUnit\Framework\TestCase;
use Surreal\classes\CBOR;

class CBORTest extends TestCase
{
    public function testCBOR()
    {
        $encoded = CBOR::encode(["id" => "123", "result" => "test"]);
        $this->assertIsString($encoded);

        try {
            $decoded = CBOR::decode($encoded);
            $this->assertEquals(["id" => "123", "result" => "test"], $decoded);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}