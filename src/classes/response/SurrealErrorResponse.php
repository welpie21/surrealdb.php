<?php

namespace Surreal\classes\response;

use Exception;

class SurrealErrorResponse extends Exception
{
    /**
     * @throws Exception
     */
    public function __construct(array $response)
    {
        $message = $response["information"] ?? $response["result"];
        $message = "SurrealDB Error: " . $message;

        parent::__construct($message, $this->code);
    }
}