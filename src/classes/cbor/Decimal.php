<?php

namespace Surreal\classes\cbor;

use CBOR\CBORObject;
use CBOR\Normalizable;
use CBOR\OtherObject\DoublePrecisionFloatObject;
use CBOR\OtherObject\SinglePrecisionFloatObject;
use CBOR\Tag;
use Surreal\enums\SurrealCBORTag;

class Decimal extends Tag implements Normalizable
{
    public function normalize(): \Surreal\classes\types\Decimal
    {
        /** @var DoublePrecisionFloatObject|SinglePrecisionFloatObject $object */
        $object = $this->object;
        $result = $object->normalize();

        return new \Surreal\classes\types\Decimal($result);
    }

    public static function getTagId(): int
    {
        return SurrealCBORTag::DECIMAL;
    }

    public static function createFromLoadedData(int $additionalInformation, ?string $data, CBORObject $object): Tag\TagInterface
    {
        return new self($additionalInformation, $data, $object);
    }
}