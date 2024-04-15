<?php

namespace Surreal\Responses;

interface ErrorResponseInterface
{
    /**
     * @param mixed $data
     * @param int $status
     */
    public static function tryFrom(mixed $data, int $status): ?ResponseInterface;
}