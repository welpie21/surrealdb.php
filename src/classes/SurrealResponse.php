<?php

namespace Surreal\classes;

class SurrealResponse
{
    public mixed $result;
    public string $status;
    public string $time;

    public function __construct(array $input)
    {
        $this->result = $input["result"];
        $this->status = $input["status"];
        $this->time = $input["time"];
    }
}