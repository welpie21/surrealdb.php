<?php

namespace Surreal\classes\types;

class RecordId
{
    const RECORD_ID_PATTERN = '/^[a-zA-Z0-9_]+:[a-zA-Z0-9_]+$/';

    private string $record;

    /**
     * @param string $record
     * @return void
     */
    public function __constructor(string $record): void
    {
        $this->record = $record;

        if (!$this->isValid()) {
            throw new \InvalidArgumentException('Invalid Record ID');
        }
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return preg_match(self::RECORD_ID_PATTERN, $this->record) === 1;
    }
}