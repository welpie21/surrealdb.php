<?php

namespace Surreal\Interfaces;

interface Connection
{
    /**
     * Connects to the connection.
     * @return void
     */
    public function connect(): void;

    /**
     * Disconnects from the connection.
     * @return void
     */
    public function disconnect(): void;

    /**
     * Returns the URL of the connection.
     * @return string
     */
    public function url(): string;
}