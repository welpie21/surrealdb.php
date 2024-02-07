<?php

namespace Surreal\classes\types;

class Decimal
{
    private float $decimal;

    public function __construct(float $decimal)
    {
        $this->decimal = $decimal;
    }

    public function __toString(): string
    {
        return (string) $this->decimal;
    }

    //TODO: implement useful method for Decimal class (e.g. add, subtract, multiply, divide, etc.)
}