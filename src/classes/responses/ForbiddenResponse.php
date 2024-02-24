<?php

namespace Surreal\classes\responses;

use Surreal\abstracts\AbstractResponse;
use Surreal\enums\HTTPCode;

/**
 * For the forbidden response the request has to respond with "code" = 403
 * the rest doesn't matter what value it has as long as it's present in the response
 */
class ForbiddenResponse extends AbstractResponse
{
    const KEYS = ["code", "details", "information"];

    /**
     * @param array{ code: string, details: string, information: string } $response
     * @return AbstractResponse
     */
    static function parse(array $response): AbstractResponse
    {
        return new ForbiddenResponse($response);
    }
}