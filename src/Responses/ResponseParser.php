<?php

namespace Surreal\Responses;

use Beau\CborPHP\exceptions\CborException;
use InvalidArgumentException;
use JsonException;
use src\Curl\HttpContentType;
use Surreal\Cbor\CBOR;

class ResponseParser
{
    /**
     * @throws JsonException|CborException
     */
    public static function parse(HttpContentType $type, string $body)
    {
        return match ($type) {
            HttpContentType::JSON => json_decode($body, true, 512, JSON_THROW_ON_ERROR),
            HttpContentType::CBOR => CBOR::decode($body),
            HttpContentType::UTF8 => $body,
            default => throw new InvalidArgumentException('Unsupported content type')
        };
    }
}