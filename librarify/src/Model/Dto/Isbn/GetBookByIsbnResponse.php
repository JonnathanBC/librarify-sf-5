<?php

namespace App\Model\Dto\Isbn;

class GetBookByIsbnResponse
{
    private string $title;    
    private string $publishDate;
    private int $numberOfPages;    

    public function __construct(
        string $title,
        string $publishDate,
        int $numberOfPages
    ) {
        $this->title = $title;
        $this->publishDate = $publishDate;
        $this->numberOfPages = $numberOfPages;
    }
    
    public function getTitle(): string
    {
        return $this->title;
    }
    
    public function getPublishDate(): string
    {
        return $this->publishDate;
    }

    public function getNumberOfPages(): int
    {
        return $this->numberOfPages;
    }
}
