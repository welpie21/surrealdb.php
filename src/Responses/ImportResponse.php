<?php

namespace Surreal\Responses;

use src\Curl\HttpContentType;
use Surreal\Core\AbstractSurreal;

readonly class ImportResponse implements ResponseInterface
{

    public static function from(HttpContentType $type, string $body, int $status): ResponseInterface
    {
        // TODO: Implement from() method.
    }

    public static function requiredHeaders(AbstractSurreal $client): array
    {
        // TODO: Implement requiredHeaders() method.
    }
}