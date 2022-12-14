<?php

namespace App\Services\Category;

use App\Entity\Category;
use App\Repository\CategoryRepository;

class CreateCategory
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {
    }

    public function __invoke(string $name): ?Category
    {
        $category = Category::create($name);
        $this->categoryRepository->save($category);
        return $category;
    }
}
