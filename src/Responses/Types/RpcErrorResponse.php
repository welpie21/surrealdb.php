<?php

namespace Surreal\Responses\Types;

use InvalidArgumentException;
use Surreal\Exceptions\SurrealException;
use Surreal\Responses\ErrorResponseInterface;
use Surreal\Responses\ResponseInterface;

readonly class RpcErrorResponse implements ResponseInterface, ErrorResponseInterface
{
    public string $error;
    public int $status;

    public function __construct(mixed $data, int $code)
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException("Invalid response data type provided");
        }

        $this->error = $data["error"]["message"];
        $this->status = $code;
    }

    public static function from(mixed $data, int $status): RpcErrorResponse
    {
        return new self($data, $status);
    }

    /**
     * @throws SurrealException
     */
    public static function tryFrom(mixed $data, int $status): ?ResponseInterface
    {
        if ($status !== 200) {
            return self::from($data, $status);
        }

        throw new SurrealException("Unknown error response has been returned.");
    }

    public function data(): mixed
    {
        return $this->error;
    }
}