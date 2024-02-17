<?php

namespace Surreal\classes\response;

class SurrealResponse
{
    public object|array|null $result;
    public string $status;
    public string $time;

    public function __construct(array $input)
    {
        $result = $input["result"];

        $this->status = $input["status"];
        $this->time = $input["time"];

        $this->result = match (count($result)) {
            0 => null,
            1 => $result,
            default => (object)$result
        };
    }
}