<?php

namespace Surreal\interface;

interface ClosableInterface
{
    /**
     * Closes the connection to the database
     * @return void
     */
    public function close(): void;
}