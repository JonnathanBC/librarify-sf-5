<?php

namespace App\Services\Book;

use App\Repository\BookRepository;
use App\Services\FileDeleter;

class DeleteBook
{
    public function __construct(
        private GetBook $getBook,
        private BookRepository $bookRepository,
        private FileDeleter $fileDeleter
    ) {
    }

    public function __invoke(string $id)
    {
        $book = ($this->getBook)($id);
        $image = $book->getImage();
        if ($image !== null) {
            ($this->fileDeleter)($image);
        }
        $this->bookRepository->delete($book);
    }
}