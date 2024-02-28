<?php

namespace Surreal\classes\responses;

use Override;
use Surreal\abstracts\AbstractResponse;

class ErrorResponse extends AbstractResponse
{
    const array KEYS = ["code", "details", "description", "information"];

    public readonly int $code;
    public readonly mixed $details;
    public readonly string $description;
    public readonly mixed $information;

    public function __construct(array $data)
    {
        $this->code = $data["code"];
        $this->details = $data["details"];
        $this->description = $data["description"];
        $this->information = $data["information"];
    }
}