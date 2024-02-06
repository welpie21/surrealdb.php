<?php

namespace Surreal\classes\cbor;

use CBOR\CBORObject;
use CBOR\IndefiniteLengthTextStringObject;
use CBOR\Normalizable;
use CBOR\Tag;
use CBOR\TextStringObject;
use Exception;
use Surreal\enums\SurrealCBORTag;

class Duration extends Tag implements Normalizable
{
    /**
     * @throws Exception
     */
    public function __constructor(int $additionalInformation, ?string $data, CBORObject $object): void
    {
        parent::__construct($additionalInformation, $data, $object);
    }

    /**
     * @throws Exception
     */
    public function normalize(): \Surreal\classes\utils\Duration
    {
        /** @var TextStringObject|IndefiniteLengthTextStringObject $object */
        $object = $this->object;
        $result = $object->normalize();

        $date = new \DateTime($result);

        return new \Surreal\classes\utils\Duration($date);
    }

    public static function getTagId(): int
    {
        return SurrealCBORTag::DURATION;
    }

    public static function createFromLoadedData(int $additionalInformation, ?string $data, CBORObject $object): Tag\TagInterface
    {
        return new self($additionalInformation, $data, $object);
    }
}