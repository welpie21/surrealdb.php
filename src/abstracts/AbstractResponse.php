<?php

namespace Surreal\abstracts;

use Exception;

abstract class AbstractResponse
{
    public function __construct(
        protected array $response
    )
    {
    }

    /**
     * Validates if the response is valid
     * @param array $response
     * @return AbstractResponse
     * @throws Exception - can throw exception (error or invalid response)
     */
    abstract static function parse(array $response): AbstractResponse;

    /**
     * Validates if the response is valid
     * @return array
     */
    public function getResponse(): array
    {
        return $this->response;
    }
}