<?php

namespace Surreal\classes\cbor;

use CBOR\CBORObject;
use CBOR\IndefiniteLengthTextStringObject;
use CBOR\Normalizable;
use CBOR\Tag;
use CBOR\TextStringObject;
use Exception;
use InvalidArgumentException;
use Surreal\enums\SurrealCBORTag;

class UUID extends Tag implements Normalizable
{
    /**
     * @throws Exception
     */
    public function __construct(int $additionalInformation, ?string $data, CBORObject $object)
    {
        if (!$object instanceof TextStringObject && !$object instanceof IndefiniteLengthTextStringObject) {
            throw new InvalidArgumentException('This tag only accepts a Byte String object.');
        }

        parent::__construct($additionalInformation, $data, $object);
    }

    /**
     * @throws Exception
     */
    public function normalize(): ?string
    {
        /** @var TextStringObject|IndefiniteLengthTextStringObject $object */
        $object = $this->object;
        $result = $object->normalize();

        return new \Surreal\classes\types\UUID($result);
    }

    public static function getTagId(): int
    {
        return SurrealCBORTag::UUID;
    }

    /**
     * @throws Exception
     */
    public static function createFromLoadedData(int $additionalInformation, ?string $data, CBORObject $object): Tag\TagInterface
    {
        return new self($additionalInformation, $data, $object);
    }
}