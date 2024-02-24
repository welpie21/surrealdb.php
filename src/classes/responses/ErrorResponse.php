<?php

namespace Surreal\classes\responses;

use Surreal\abstracts\AbstractResponse;
use Surreal\classes\exceptions\SurrealException;
use Surreal\enums\HTTPCode;
use Exception;

class ErrorResponse extends AbstractResponse
{
    const KEYS = ["code", "details", "description", "information"];

    /**
     * @param array{ code: string, details: string, description: string, information: string } $response
     * @return AbstractResponse
     * @throws Exception
     */
    public static function parse(array $response): AbstractResponse
    {
        return new ErrorResponse($response);
    }
}