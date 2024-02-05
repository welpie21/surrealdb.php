<?php

namespace Surreal\classes\cbor;

use Exception;
use \DateTime;
use Surreal\interfaces\CBORInterface;

readonly class Duration implements CBORInterface
{
    public DateTime $date;

    /**
     * @throws Exception
     */
    public function __constructor($data): void
    {
        $this->date = new DateTime($data);
    }

    public function __toString(): string
    {
        return $this->date->format('Y-m-d H:i:s');
    }

    public function getMilliseconds(): int
    {
        return $this->date->getTimestamp() * 1000;
    }

    public function getSeconds(): int
    {
        return $this->date->getTimestamp();
    }

    public function getMinutes(): int
    {
        return $this->date->getTimestamp() / 60;
    }

    public function getHours(): int
    {
        return $this->date->getTimestamp() / 3600;
    }

    public function getDays(): int
    {
        return $this->date->getTimestamp() / 86400;
    }

    public function getWeeks(): int
    {
        return $this->date->getTimestamp() / 604800;
    }

    public function getMonths(): int
    {
        return $this->date->getTimestamp() / 2628000;
    }

    public function getYears(): int
    {
        return $this->date->getTimestamp() / 31536000;
    }
}