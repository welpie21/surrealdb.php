<?php

namespace Surreal\Responses\Types;

use InvalidArgumentException;
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

        $this->error = $data["error"];
        $this->status = $code;
    }

    public static function from(mixed $data, int $status)
    {
        return new self($data, $status);
    }

    public static function tryFrom(mixed $data, int $status): ?ResponseInterface
    {
        if ($status !== 200) {
            return self::from($data, $status);
        }

        // Sometimes surreal responses doesn't give a correct
        // status code. So I have to check additionally if the
        // response is an error response.
        if (is_array($data) && array_key_exists("error", $data)) {
            return new self($data, $status);
        }

        return null;
    }

    public function data(): mixed
    {
        return $this->error;
    }
}