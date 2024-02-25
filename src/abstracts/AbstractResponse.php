<?php

namespace Surreal\abstracts;

use Exception;

abstract class AbstractResponse
{
    public function __construct(
        protected array $data
    )
    {
    }

    /**
     * Validates if the response is valid
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}