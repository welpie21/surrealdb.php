<?php

namespace Surreal\Responses\Types;

use Exception;
use InvalidArgumentException;
use Surreal\Curl\HttpStatus;
use Surreal\Responses\ErrorResponseInterface;
use Surreal\Responses\ResponseInterface;

readonly class RpcResponse implements ResponseInterface, ErrorResponseInterface
{
    public int $id;
    public mixed $result;

    public function __construct(mixed $data)
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException("Invalid response data type provided");
        }

        var_dump($data);

        $this->id = $data["id"];
        $this->result = $data["result"];
    }

    /**
     * @throws Exception
     */
    public static function from(mixed $data, int $status): ResponseInterface
    {
        var_dump($status);
        switch ($status) {
            case 200:
                return new self($data);
            case 500:
                $error = RpcErrorResponse::tryFrom($data, $status);
                if ($error !== null) {
                    return $error;
                }
                break;
            default:
                throw new Exception("Invalid status code");
        }

        throw new Exception("Invalid response data");
    }

    /**
     * @throws Exception
     */
    public static function tryFrom(mixed $data, int $status): ?ResponseInterface
    {
        throw new Exception("Not implemented");
    }

    public function data(): mixed
    {
        return $this->result;
    }
}