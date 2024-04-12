<?php

namespace Surreal\Responses;

class AnyResponse implements ResponseInterface
{
    public array $response;

    public function __construct(array $input)
    {
        $this->response = $input;
    }
}