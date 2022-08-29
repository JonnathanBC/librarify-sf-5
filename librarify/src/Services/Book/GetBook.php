<?php

namespace App\Services\Book;

use App\Entity\Book;
use App\Repository\BookRepository;
use Ramsey\Uuid\Uuid;

class GetBook
{
    private BookRepository $bookRepository;

    public function __construct(BookRepository $bookRepository) {
        $this->bookRepository = $bookRepository;
    }

    public function __invoke(string $id): ?Book
    {
        return $this->bookRepository->find(Uuid::fromString($id));
    }
}
