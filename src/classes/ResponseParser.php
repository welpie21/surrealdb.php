<?php

namespace Surreal\classes;

use Surreal\abstracts\AbstractResponse;
use Surreal\classes\responses\AnyResponse;
use Surreal\classes\responses\AuthResponse;
use Surreal\classes\responses\ErrorResponse;
use Surreal\classes\responses\ForbiddenResponse;
use Surreal\classes\responses\QueryResponse;
use Exception;
use Surreal\classes\responses\WebsocketResponse;

readonly class ResponseParser
{
    private ?AbstractResponse $response;

    /**
     * @throws Exception
     */
    public function __construct(array $input)
    {
        $this->response = match (array_keys($input)) {
            AuthResponse::KEYS => new AuthResponse($input),
            ErrorResponse::KEYS => new ErrorResponse($input),
            QueryResponse::KEYS => new QueryResponse($input),
            ForbiddenResponse::KEYS => new ForbiddenResponse($input),
            WebsocketResponse::KEYS => new WebsocketResponse($input),
            default => new AnyResponse($input),
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