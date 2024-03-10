<?php

namespace parsers;

use PHPUnit\Framework\TestCase;
use Surreal\classes\CBOR;

class CBORTest extends TestCase
{
    public function testEncode()
    {
        $encoded = CBOR::encode(["id" => "123", "result" => "test"]);
        var_dump($encoded);

        $this->assertEquals("a261696431323366726573756c74", $encoded);
    }

    /**
     * @throws \Exception
     */
    public function testDecode()
    {
        try {
            $decoded = CBOR::decode("a261696431323366726573756c74");
            var_dump($decoded);

            $this->assertEquals(["id" => "123", "result" => "test"], $decoded);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}