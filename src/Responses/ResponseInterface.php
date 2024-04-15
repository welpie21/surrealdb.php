<?php

namespace Surreal\Responses;

interface ResponseInterface
{
    /**
     * Parse the response body and return a new instance of the class
     * @param mixed $data
     * @param int $status
     */
    public static function from(mixed $data, int $status);

    /**
     * Returns the response from the request.
     * @return mixed
     */
    public function data(): mixed;
}