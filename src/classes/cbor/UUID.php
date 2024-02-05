<?php

namespace Surreal\classes\cbor;

use Exception;
use Ramsey\Uuid\Uuid;
use Surreal\interfaces\CBORInterface;

readonly class UUID implements CBORInterface
{
    public ?string $uuid;

    /**
     * @throws Exception
     */
    public function __construct($data)
    {
        $uuid = !$data ? Uuid::uuid4() : $data;

        if(!Uuid::isValid($uuid)) {
            throw new Exception('Invalid UUID');
        }

        $this->uuid = $uuid;
    }
}