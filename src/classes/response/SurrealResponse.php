<?php

namespace Surreal\classes\response;

class SurrealResponse
{
    public object|array|null $result = null;
    public string $status;
    public string $time;

    /**
     * @throws SurrealErrorResponse
     */
    public function __construct(array|null $input)
    {
        $result = $input["result"];

        $this->status = $input["status"];
        $this->time = $input["time"];

        if($this->status == "ERR") {
            throw new SurrealErrorResponse($input);
        }

        $this->result = match (true) {
            count($result) === 1 => $result[0],
            default => $result
        };
    }
}