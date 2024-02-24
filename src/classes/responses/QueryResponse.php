<?php

namespace Surreal\classes\responses;

use Surreal\abstracts\AbstractResponse;

class QueryResponse extends AbstractResponse
{
    const KEYS = ["code", "details", "time"];

    /**
     * @param array{code: string, details: string, time: string} $response
     * @return AbstractResponse
     */
    public static function parse(array $response): AbstractResponse
    {
        return new QueryResponse($response);
    }
}