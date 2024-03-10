<?php

namespace Surreal\classes\responses;


use Surreal\interface\ResponseInterface;

class AnyResponse implements ResponseInterface
{
    public array $response;

    public function __construct(array $input)
    {
        $this->response = $input;
    }
}