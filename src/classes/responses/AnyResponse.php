<?php

namespace Surreal\classes\responses;

use Surreal\abstracts\AbstractResponse;

class AnyResponse extends AbstractResponse
{
    public array $response;

    public function __construct(array $input)
    {
        $this->response = $input;
    }
}