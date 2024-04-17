<?php

namespace Surreal\Cbor;

use Beau\CborPHP\CborDecoder;
use Beau\CborPHP\CborEncoder;
use Beau\CborPHP\classes\TaggedValue;
use Beau\CborPHP\exceptions\CborException;
use Beau\CborPHP\utils\CborByteString;
use Exception;
use Surreal\Cbor\Types\RecordId;

class CBOR
{
    /**
     * Encodes data to CBOR
     * @param mixed $data
     * @return string|null
     * @throws CborException
     */
    public static function encode(mixed $data): ?string
    {
        return CborEncoder::encode($data, function ($key, $value) {

            if ($value instanceof RecordId) {
                return new TaggedValue(8, (string)$value);
            }

            return $value;
        });
    }

    /**
     * Decodes CBOR data
     * @param string $data
     * @return mixed
     * @throws Exception
     */
    public static function decode(string $data): mixed
    {
        return CborDecoder::decode($data, function ($key, $value) {

            if(!($value instanceof TaggedValue)) {
                return $value;
            }

            return match ($value->tag) {
                8 => RecordId::fromArray($value->value),
                default => throw new CborException("Unknown tag: " . $value->tag)
            };
        });
    }
}