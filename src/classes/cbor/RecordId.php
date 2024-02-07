<?php

namespace Surreal\classes\cbor;

use CBOR\CBORObject;
use CBOR\IndefiniteLengthTextStringObject;
use CBOR\Normalizable;
use CBOR\Tag;
use CBOR\TextStringObject;
use Surreal\enums\SurrealCBORTag;

class RecordId extends Tag implements Normalizable
{
    public function __constructor(int $additionalInformation, ?string $data, CBORObject $object): void
    {
        parent::__construct($additionalInformation, $data, $object);
    }

    /**
     * @return string
     */
    public function normalize(): string
    {
        /** @var TextStringObject|IndefiniteLengthTextStringObject $object */
        $object = $this->object;
        return $object->normalize();
    }

    public static function getTagId(): int
    {
        return SurrealCBORTag::RECORD_ID;
    }

    public static function createFromLoadedData(int $additionalInformation, ?string $data, CBORObject $object): Tag\TagInterface
    {
        return new self($additionalInformation, $data, $object);
    }
}