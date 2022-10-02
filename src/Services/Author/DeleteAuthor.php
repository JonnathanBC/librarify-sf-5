<?php

namespace App\Services\Author;

use App\Repository\AuthorRepository;
use App\Services\Author\GetAuthor;

class DeleteAuthor
{
    public function __construct(
        private GetAuthor $getAuthor,
        private AuthorRepository $authorRepository
    ) {
    }

    public function __invoke(string $id)
    {
        $author = ($this->getAuthor)($id);
        $this->authorRepository->delete($author);
    }
}