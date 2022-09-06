<?php

namespace App\Services\Author;

use App\Repository\AuthorRepository;
use App\Service\Author\GetAuthor;

class DeleteAuthor
{
    private GetAuthor $getAuthor;
    private AuthorRepository $authorRepository;

    public function __construct(
        GetAuthor $getAuthor,
        AuthorRepository $authorRepository
    ) {
        $this->getAuthor = $getAuthor;
        $this->authorRepository = $authorRepository;
    }

    public function __invoke(string $id)
    {
        $book = ($this->getAuthor)($id);
        $this->authorRepository->delete($book);
    }
}