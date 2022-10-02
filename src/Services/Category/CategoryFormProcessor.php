<?php

namespace App\Services\Category;

use App\Entity\Category;
use App\Form\Model\CategoryDto;
use App\Form\Type\CategoryFormType;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class CategoryFormProcessor 
{
    public function __construct(
        private GetCategory $getCategory,
        private FormFactoryInterface $formFactoryInterface,
        private CategoryRepository $categoryRepository
    ) {
    }

    public function __invoke(Request $request, ?string $categoryId = null): array
    {
        $category = null;
        $categoryDto = null;

        if ($categoryId === null) {
            $categoryDto = new CategoryDto();
        } else {
            $category = ($this->getCategory)($categoryId);
            $categoryDto = CategoryDto::createFromCategory($category);
        }

        // handleForm
        $form = $this->formFactoryInterface->create(CategoryFormType::class, $categoryDto);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return [null, 'Form is not submitted'];
        }
        if(!$form->isValid()) {
            return [null, $form];
        }

        // Creation Category
        if ($category === null) {
            $category = Category::create($categoryDto->name);
        } else {
            $category->update($categoryDto->name);
        }

        $this->categoryRepository->save($category);
        return [$category, null];
    }
}