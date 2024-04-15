<?php

namespace Surreal\Responses\Types;

use Surreal\Responses\ResponseInterface;

readonly class ImportResponse implements ResponseInterface
{
    public function __construct(mixed $data)
    {
    }

    public static function from(mixed $data, int $status)
    {
        // TODO: Implement from() method.
    }

    public function data(): mixed
    {
        // TODO: Implement data() method.
    }
}