<?php

namespace Surreal\abstracts;

abstract class AbstractResponse
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
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