<?php

namespace Surreal\classes;

use CBOR\Decoder;
use CBOR\Tag;

readonly class CBORHandler
{
    private Decoder $decoder;
    private Tag\DatetimeTag $datetimeTag;

    public function __construct()
    {
        $this->decoder = new Decoder();
    }

    public function encode(mixed $data)
    {

    }

    public function decode(string $data)
    {

    }
}