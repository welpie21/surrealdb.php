<?php

namespace Surreal\classes\cbor;

use Exception;
use Surreal\interfaces\CBORInterface;

readonly class UUID implements CBORInterface
{
    public ?string $uuid;

    /**
     * @throws Exception
     */
    public function __construct($data)
    {
        $uuid = !$data ? \Ramsey\Uuid\Uuid::uuid4()->toString() : $data;

        if (!\Ramsey\Uuid\Uuid::isValid($uuid)) {
            throw new Exception('Invalid UUID');
        }

        $this->uuid = $uuid;
    }
}