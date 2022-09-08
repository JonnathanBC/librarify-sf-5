<?php

namespace App\Form\Model;

use App\Entity\Book;
use DateTimeInterface;

class BookDto
{
    public ?string $title = null;
    public ?string $base64Image = null;
    public ?string $description = null;
    public ?int $score = null;
    public ?DateTimeInterface $readAt = null;
    /** @var \App\Form\Model\CategoryDto[]|null */
    public ?array $categories = [];
    /** @var \App\Form\Model\AuthorDto[]|null */
    public ?array $authors = [];

    public function __construct()
    {
        $this->categories = [];
    }

    public static function createEmpty(): self
    {
        return new self();
    }

    public static function createFromBook(Book $book): self
    {
        $dto = new self();
        $dto->title = $book->getTitle();
        $dto->score = $book->getScore()->getValue();
        $dto->description = $book->getDescription();
        $dto->readAt = $book->getReadAt();
        return $dto;
    }
}
