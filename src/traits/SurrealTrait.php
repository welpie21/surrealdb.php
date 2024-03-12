<?php

namespace Surreal\traits;

trait SurrealTrait
{
    private function parseThing(string $thing): array
    {
        return explode(":", $thing);
    }
}