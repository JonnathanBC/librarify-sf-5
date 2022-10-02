<?php

namespace App\Form\Model;

use App\Entity\Book;
use DateTimeInterface;

class BookDto
{
    public function __construct(
        public ?string $title = null,
        public ?string $base64Image = null,
        public ?string $description = null,
        public ?int $score = null,
        public ?DateTimeInterface $readAt = null,
        /** @var \App\Form\Model\CategoryDto[]|null */
        public ?array $categories = [],
        /** @var \App\Form\Model\AuthorDto[]|null */
        public ?array $authors = [],
    ) {
    }

    public static function createEmpty(): self
    {
        return new self();
    }

    public static function createFromBook(Book $book): self
    {
        return new self(
            $book->getTitle(),
            $book->getScore(),
            $book->getDescription(),
            $book->getReadAt()
        );
    }
}
