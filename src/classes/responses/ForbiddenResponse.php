<?php

namespace Surreal\classes\responses;

use Override;
use Surreal\abstracts\AbstractResponse;

/**
 * For the forbidden response the request has to respond with "code" = 403
 * the rest doesn't matter what value it has as long as it's present in the response
 */
class ForbiddenResponse extends AbstractResponse
{
    const array KEYS = ["code", "details", "information"];

    /**
     * @return array{code: int, details: string, information: string}
     */
    #[Override] public function getData(): array
    {
        return parent::getData();
    }
}