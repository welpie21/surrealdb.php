<?php

namespace Surreal\classes\responses;

use Override;
use Surreal\abstracts\AbstractResponse;

class ErrorResponse extends AbstractResponse
{
    const array KEYS = ["code", "details", "description", "information"];

    /**
     * @return array{code: int, details: string, description: string, information: string}
     */
    #[Override] public function getData(): array
    {
        return parent::getData();
    }
}