<?php

namespace Surreal\Responses\Types;

use Exception;
use InvalidArgumentException;
use Surreal\Curl\HttpContentType;
use Surreal\Exceptions\SurrealException;
use Surreal\Responses\ErrorResponseInterface;
use Surreal\Responses\ResponseInterface;
use Surreal\Utils\ArrayHelper;

readonly class RpcResponse implements ResponseInterface, ErrorResponseInterface
{
    public mixed $result;

    public function __construct(mixed $data)
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException("Invalid response data type provided");
        }

        $isAssoc = ArrayHelper::isAssoc($data);

        if($isAssoc) {
            if(array_key_exists("result", $data)) {
                $this->result = $data["result"];
            } else {
                $this->result = null;
            }
        } else {
            $this->result = $data;
        }
    }

    /**
     * @throws Exception
     */
    public static function from(mixed $data, HttpContentType $type,int $status): ResponseInterface
    {
        switch ($status) {
            case 200:
                if (ArrayHelper::isAssoc($data)) {
                    if (array_key_exists("error", $data)) {
                        return RpcErrorResponse::from($data, $status);
                    }
                }

                return new self($data);
            case 500:
                $error = RpcErrorResponse::tryFrom($data, $status);
                if ($error !== null) {
                    return $error;
                }
                break;
            default:
                throw new SurrealException("Invalid status code: $status");
        }

        throw new Exception("Invalid response data");
    }

    /**
     * @throws Exception
     */
    public static function tryFrom(mixed $data, int $status): ?ResponseInterface
    {
        return match (true) {
            $status === 200 => self::from($data, $status),
            $status === 500 => RpcErrorResponse::tryFrom($data, $status)
        };
    }

    public function data(): mixed
    {
        return $this->result;
    }
}