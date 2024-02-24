<?php

namespace Surreal\abstracts;

abstract class AbstractProtocol extends AbstractSurreal
{
    /**
     * Set the timeout for the protocol
     * @param int $timeout
     * @return void
     */
    abstract public function setTimeout(int $timeout): void;
}