<?php

namespace Surreal\classes\responses;

use Override;
use Surreal\abstracts\AbstractResponse;

class QueryResponse extends AbstractResponse
{
    const array KEYS = ["code", "details", "time"];

    public readonly int $code;
    public readonly mixed $details;
    public readonly int $time;

    public function __construct(array $data)
    {
        $this->code = $data["code"];
        $this->details = $data["details"];
        $this->time = $data["time"];
    }
}