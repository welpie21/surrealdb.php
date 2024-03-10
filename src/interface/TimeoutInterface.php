<?php

namespace Surreal\interface;

interface TimeoutInterface
{
    /**
     * Set a timeout for the request in seconds
     * @param int $seconds
     * @return void
     */
    public function setTimeout(int $seconds): void;

    /**
     * Get the timeout for the request in seconds
     * @return int
     */
    public function getTimeout(): int;
}