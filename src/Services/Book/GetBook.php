<?php

namespace App\Services\Book;

use App\Entity\Book;
use App\Model\Exception\Book\BookNotFound;
use App\Repository\BookRepository;
use Ramsey\Uuid\Uuid;

class GetBook
{
    public function __construct(
        private BookRepository $bookRepository
    ) {
    }

    public function __invoke(string $id): ?Book
    {
        $book = $this->bookRepository->find(Uuid::fromString($id));
        if (!$book) {
            BookNotFound::throwException();
        }
        return $book;
    }
}
