<?php

namespace App\Services\Category;

use App\Repository\CategoryRepository;

class DeleteCategory
{

    public function __construct(
        private GetCategory $getCategory,
        private CategoryRepository $categoryRepository
    ) {
    }

    public function __invoke(string $id)
    {
        $category = ($this->getCategory)($id);
        $this->categoryRepository->delete($category);
    }
}