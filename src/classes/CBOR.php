<?php

namespace Surreal\classes;

use Exception;
use \CBOR\CBOREncoder;

class CBOR
{
    /**
     *
     * @param mixed $data
     * @return string|null
     */
    public static function encode(mixed $data): ?string
    {
        return bin2hex(CBOREncoder::encode($data));
    }

    /**
     * @codeCoverageIgnore - Not being used in the application yet.
     * @param string $data
     * @return mixed
     * @throws Exception
     */
    public static function decode(string $data): mixed
    {
        return CBOREncoder::decode($data);
    }
}