<?php

namespace Surreal\classes;

use CBOR\CBORObject;
use CBOR\Decoder;
use CBOR\OtherObject\HalfPrecisionFloatObject;
use CBOR\OtherObject\OtherObjectManager;
use CBOR\OtherObject\SimpleObject;
use CBOR\OtherObject\SinglePrecisionFloatObject;
use CBOR\StringStream;
use CBOR\Tag\DatetimeTag;
use CBOR\Tag\DecimalFractionTag;
use CBOR\Tag\TagManager;
use CBOR\Tag\UnsignedBigIntegerTag;
use CBOR\TextStringObject;
use Exception;
use Surreal\classes\cbor\Decimal;
use Surreal\classes\cbor\Duration;
use Surreal\classes\cbor\RecordId;
use Surreal\classes\cbor\UUID;

class CBORHandler
{
    private Decoder $decoder;
    private OtherObjectManager $objectManager;
    private TagManager $tagManager;

    private static self $instance;

    public function __construct()
    {
        $this->objectManager = OtherObjectManager::create()
            ->add(SimpleObject::class)
            ->add(HalfPrecisionFloatObject::class)
            ->add(SinglePrecisionFloatObject::class);

        $this->tagManager = TagManager::create()
            ->add(DatetimeTag::class)
            ->add(UnsignedBigIntegerTag::class)
            ->add(DecimalFractionTag::class)
            ->add(Decimal::class)
            ->add(Duration::class)
            ->add(RecordId::class)
            ->add(UUID::class);

        $this->decoder = Decoder::create($this->tagManager, $this->objectManager);
    }

    /**
     * @throws Exception
     */
    public function encode(mixed $data)
    {
        foreach ($data as $key => $value) {
            if ($value instanceof Decimal) {
                $data[$key] = DecimalFractionTag::create($value)->getData();
            } elseif ($value instanceof Duration) {
                $data[$key] = $value->normalize();
            } elseif ($value instanceof RecordId) {
                $data[$key] = UnsignedBigIntegerTag::create($value)->getData();
            } elseif ($value instanceof UUID) {
                $data[$key] = $value->normalize();
            } elseif ($value instanceof DatetimeTag) {
                $data[$key] = DatetimeTag::create($value)->getData();
            } elseif (is_array($value)) {
                $data[$key] = $this->encode($value);
            }
        }

        return $data;
    }

    /**
     * @param string $data
     * @return CBORObject
     */
    public function decode(string $data): CBORObject
    {
        $data = hex2bin($data);
        $stream = StringStream::create($data);

        return $this->decoder->decode($stream);
    }

    public static function getInstance(): CBORHandler
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}