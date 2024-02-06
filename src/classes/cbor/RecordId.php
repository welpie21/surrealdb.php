<?php

namespace Surreal\classes\cbor;

use CBOR\CBORObject;
use CBOR\Normalizable;
use CBOR\Tag;
use CBOR\Tag\TagInterface;
use Surreal\enums\SurrealCBORTag;

class RecordId extends Tag implements Normalizable
{
    public string $table;
    public string $id;

    public function __constructor(int $additionalInformation, ?string $data, CBORObject $object): void
    {
        parent::__construct($additionalInformation, $data, $object);
    }

    public function normalize()
    {
        // TODO: Implement normalize() method.
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