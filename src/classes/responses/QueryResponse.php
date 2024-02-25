<?php

namespace Surreal\classes\responses;

use Override;
use Surreal\abstracts\AbstractResponse;

class QueryResponse extends AbstractResponse
{
    const array KEYS = ["code", "details", "time"];

    /**
     * @return array{code: string, details: string, time: string}
     */
    #[Override] public function getData(): array
    {
        return parent::getData();
    }
}