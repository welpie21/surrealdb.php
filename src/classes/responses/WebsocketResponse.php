<?php

namespace Surreal\classes\responses;

use Override;
use Surreal\abstracts\AbstractResponse;

class WebsocketResponse extends AbstractResponse
{
    const array KEYS = ["id", "result"];

    /**
     * @return array{id: string, result: mixed}
     */
    #[Override] public function getData(): array
    {
        return parent::getData();
    }
}