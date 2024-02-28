<?php

namespace Surreal\classes\responses;

use Surreal\abstracts\AbstractResponse;

/**
 * Import response returns an array with indexes ( int ).
 * So we have to check if this is a response of an array or not.
 */
class AnyResponse extends AbstractResponse
{
    public readonly mixed $response;

    public function __construct(array $data)
    {
        $this->response = $data;
    }
}