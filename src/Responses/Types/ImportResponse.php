<?php

namespace Surreal\Responses\Types;

use Surreal\Responses\ResponseInterface;

readonly class ImportResponse implements ResponseInterface
{
    public mixed $result;
    public int $status;

    public function __construct(mixed $data, int $status)
    {
        $this->result = $data;
        $this->status = $status;
    }

    public static function from(mixed $data, int $status): ResponseInterface
    {
        if($status !== 200) {
            $error = ImportErrorResponse::tryFrom($data, $status);
            if($error) {
                return $error;
            }
        }

        return new self($data, $status);
    }

    public function data(): mixed
    {
        return $this->result;
    }
}