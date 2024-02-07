<?php

namespace Surreal\classes\types;

use Exception;
use \Ramsey\Uuid\Uuid as RamseyUuid;

class UUID
{
    private string $uuid;

    /**
     * @throws Exception
     */
    public function __construct(?string $uuid)
    {
        $_uuid = $uuid ?? RamseyUuid::uuid4()->toString();

        if (!RamseyUuid::isValid($_uuid)) {
            throw new \InvalidArgumentException('Invalid UUID');
        }

        $this->uuid = $_uuid;
    }

    public function __toString(): string
    {
        return $this->uuid;
    }
}