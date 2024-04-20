<?php

namespace Surreal\Responses\Types;

use Surreal\Curl\HttpContentType;
use Surreal\Exceptions\SurrealException;
use Surreal\Responses\ResponseInterface;

readonly class StringResponse implements ResponseInterface
{
    private string $body;

    public function __construct(string $data)
    {
        $this->body = $data;
    }

    /**
     * @throws SurrealException
     */
    public static function from(mixed $data,HttpContentType $type, int $status): StringResponse
    {
        if($status !== 200) {
            $error = StringErrorResponse::tryFrom($data, $type, $status);
            if($error) {
                throw new SurrealException($error->error, $error->status);
            }
        }

        return new self($data);
    }

    public function data(): string
    {
        return $this->body;
    }
}