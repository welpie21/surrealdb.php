<?php

namespace parsers;

use PHPUnit\Framework\TestCase;
use Surreal\classes\CBOR;

class CBORTest extends TestCase
{
    public function testCBOR()
    {
        $encoded = CBOR::encode(["id" => "123", "result" => "test"]);

        $this->assertEquals("a26269646331323366726573756c746474657374", $encoded);

        try {
            $decoded = CBOR::decode("a26269646331323366726573756c746474657374");

            $this->assertEquals(["id" => "123", "result" => "test"], $decoded);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}