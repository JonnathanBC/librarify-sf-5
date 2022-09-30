<?php

namespace App\Services;

use League\Flysystem\FilesystemOperator;

class FileDeleter 
{
    public function __construct(
        private FilesystemOperator $defaultStorage
    ) {
    }

    public function __invoke(string $path): void
    {
        $this->defaultStorage->delete($path);
    }
}