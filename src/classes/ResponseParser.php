<?php

namespace Surreal\classes;

use Surreal\classes\responses\AnyResponse;
use Surreal\classes\responses\AuthResponse;
use Surreal\classes\responses\HTTPErrorResponse;
use Surreal\classes\responses\ForbiddenResponse;
use Exception;
use Surreal\classes\responses\WebsocketErrorResponse;
use Surreal\classes\responses\WebsocketResponse;
use Surreal\interface\ResponseInterface;

readonly class ResponseParser
{
    private ?ResponseInterface $response;

    /**
     * @throws Exception
     */
    private function __construct(?array $input)
    {
        $this->response = match (array_keys($input)) {
            AuthResponse::KEYS => new AuthResponse($input),
            HTTPErrorResponse::KEYS => new HTTPErrorResponse($input),
            ForbiddenResponse::KEYS => new ForbiddenResponse($input),
            WebsocketResponse::KEYS => new WebsocketResponse($input),
            WebsocketErrorResponse::KEYS => new WebsocketErrorResponse($input),
            default => new AnyResponse($input)
        };
    }

    /**
     * Returns the response
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * @throws Exception
     */
    public static function create(array $input): ResponseInterface
    {
        return (new self($input))->getResponse();
    }
}