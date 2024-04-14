<?php

namespace Surreal\Responses;

use src\Curl\HttpContentType;
use Surreal\Core\AbstractSurreal;

readonly class StringResponse implements ResponseInterface
{
    public string $body;

    public static function from(HttpContentType $type, string $body, int $status): ResponseInterface
    {
        // TODO: Implement from() method.
    }

    public static function requiredHeaders(AbstractSurreal $client): array
    {
        // TODO: Implement requiredHeaders() method.
    }
}