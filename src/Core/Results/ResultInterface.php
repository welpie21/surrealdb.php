<?php

namespace Surreal\Core\Results;

use Surreal\Core\AbstractSurreal;
use Surreal\Responses\ResponseInterface;

interface ResultInterface
{
    /**
     * Parser the response into a result.
     * @param ResponseInterface $response
     * @return mixed
     */
    public static function from(ResponseInterface $response): mixed;

    /**
     * Returns required HTTP headers for the client
     * @param AbstractSurreal $client
     * @return array
     */
    public static function requiredHTTPHeaders(AbstractSurreal $client): array;
}