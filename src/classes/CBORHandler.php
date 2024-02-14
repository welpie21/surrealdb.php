<?php

namespace Surreal\classes;

use Exception;
use \CBOR\CBOREncoder;

class CBORHandler
{
    /**
     *
     * @param mixed $data
     * @return string|null
     */
    public static function encode(mixed $data): ?string
    {
        return CBOREncoder::encode($data);
    }

    /**
     * @param string $data
     * @return mixed
     * @throws Exception
     */
    public static function decode(string $data): mixed
    {
        return CBOREncoder::decode($data);
    }
}