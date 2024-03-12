<?php

namespace Surreal\classes;

readonly class SurrealPatch
{
    private function __construct(
        private string $operation,
        private string $path,
        private mixed $value
    ) {}

    public static function create(string $operation, string $path, mixed $value): array
    {
        $patch = new self($operation, $path, $value);
        return $patch->toArray();
    }

    public function toArray(): array
    {
        return [
            "op" => $this->operation,
            "path" => $this->path,
            "value" => $this->value
        ];
    }
}