<?php

namespace Surreal\classes;

use Surreal\abstracts\AbstractResponse;
use Surreal\classes\responses\AuthResponse;
use Surreal\classes\responses\ErrorResponse;
use Surreal\classes\responses\ForbiddenResponse;
use Surreal\classes\responses\QueryResponse;
use Exception;

class Response
{
    private readonly ?AbstractResponse $response;

    /**
     * @throws Exception
     */
    public function __construct(array $input)
    {
        $this->response = match (array_keys($input)) {
            AuthResponse::KEYS => AuthResponse::parse($input),
            ErrorResponse::KEYS => ErrorResponse::parse($input),
            QueryResponse::KEYS => QueryResponse::parse($input),
            ForbiddenResponse::KEYS => ForbiddenResponse::parse($input),
            default => throw new Exception("Invalid response received.")
        };
    }

    /**
     * Returns the response
     * @return AbstractResponse
     */
    public function getResponse(): AbstractResponse
    {
        return $this->response;
    }
}