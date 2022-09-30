<?php

namespace App\Model\Dto\Isbn;

class GetBookByIsbnResponse
{
    public function __construct(
        readonly public string $title,
        readonly public string $publishDate,
        readonly public int $numberOfPages
    ) {
    }
}
