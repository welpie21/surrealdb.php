<?php

namespace Surreal\Responses;

use src\Curl\HttpContentType;

interface ResponseInterface
{
    /**
     * Parse the response body and return a new instance of the class
     * @param HttpContentType $type
     * @param string $body
     * @param int $status
     * @return ResponseInterface
     */
    public static function from(HttpContentType $type, string $body, int $status): ResponseInterface;
}