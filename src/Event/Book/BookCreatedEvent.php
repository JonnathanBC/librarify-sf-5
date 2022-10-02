<?php

namespace App\Event\Book;

use Ramsey\Uuid\UuidInterface;
use Symfony\Contracts\EventDispatcher\Event;

class BookCreatedEvent extends Event
{
    public const NAME = 'book.created';

    public function __construct(
        private UuidInterface $bookId
    ) {
    }

    public function getBookId(): UuidInterface
    {
        return $this->bookId;
    }
}
