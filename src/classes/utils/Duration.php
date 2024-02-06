<?php

namespace Surreal\classes\utils;

class Duration
{
    private \DateTime $dateTime;
    private int $timestamp;

    public function __construct(\DateTime $dateTime)
    {
        $this->dateTime = $dateTime;
        $this->timestamp = $dateTime->getTimestamp();
    }

    public function __toString(): string
    {
        return $this->dateTime->format('P%yY%mM%dDT%hH%iM%sS');
    }

    public function getYears(): int
    {
        return $this->timestamp / 31536000;
    }

    public function getMonths(): int
    {
        return $this->timestamp / 2592000;
    }

    public function getWeeks(): int
    {
        return $this->timestamp / 604800;
    }

    public function getDays(): int
    {
        return $this->timestamp / 86400;
    }

    public function getHours(): int
    {
        return $this->timestamp / 3600;
    }

    public function getMinutes(): int
    {
        return $this->timestamp / 60;
    }

    public function getSeconds(): int
    {
        return $this->timestamp;
    }

    public function getMilliseconds(): int
    {
        return $this->timestamp * 1000;
    }

    public function getMicroseconds(): int
    {
        return $this->timestamp * 1000000;
    }
}