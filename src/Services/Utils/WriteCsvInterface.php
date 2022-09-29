<?php

namespace App\Services\Utils;

interface WriteCsvInterface
{
    public function write(
        iterable $elements,
        string $path,
        array $headers = null,
        string $mode = 'w+'
    ): void;
}