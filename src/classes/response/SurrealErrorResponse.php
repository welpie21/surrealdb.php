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
        $message = "SurrealDB Error: " . $response["information"];
        parent::__construct($message, $this->code);
    }
}