<?php

namespace Surreal\Responses;

use Exception;
use Surreal\Exceptions\SurrealException;
use Surreal\Responses\Auth\AuthResponse;
use Surreal\Responses\Error\ForbiddenResponse;
use Surreal\Responses\Http\HTTPErrorResponse;
use Surreal\Responses\Rpc\RpcMessageResponse;
use Surreal\Responses\Websocket\RpcMessageErrorResponse;

readonly class ResponseParser
{
    private ?ResponseInterface $response;

    /**
     * @throws SurrealException
     */
    private function __construct(?array $input)
    {
        $this->response = match (array_keys($input)) {
            AuthResponse::KEYS => new AuthResponse($input),
            HTTPErrorResponse::KEYS => new HTTPErrorResponse($input),
            ForbiddenResponse::KEYS => new ForbiddenResponse($input),
            RpcMessageResponse::KEYS => new RpcMessageResponse($input),
            RpcMessageErrorResponse::KEYS => new RpcMessageErrorResponse($input),
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
     * @throws SurrealException
     */
    public static function create(array $input): ResponseInterface
    {
        return (new self($input))->getResponse();
    }
}