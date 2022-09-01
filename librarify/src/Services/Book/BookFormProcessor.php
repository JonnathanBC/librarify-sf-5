<?php

namespace App\Services\Book;

use App\Entity\Book;
use App\Entity\Book\Score;
use App\Form\Model\BookDto;
use App\Form\Model\CategoryDto;
use App\Form\Type\BookFormType;
use App\Repository\BookRepository;
use App\Services\Category\CreateCategory;
use App\Services\Category\GetCategory;
use App\Services\FileUploader;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class BookFormProcessor
{
    private BookRepository $bookRepository;
    private FileUploader $fileUploader;
    private FormFactoryInterface $formFactory;
    private GetBook $getBook;
    private GetCategory $getCategory;
    private CreateCategory $createCategory;

    public function __construct(
        BookRepository $bookRepository,
        FileUploader $fileUploader,
        FormFactoryInterface $formFactory,
        GetBook $getBook,
        GetCategory $getCategory,
        CreateCategory $createCategory
    ) {
        $this->bookRepository = $bookRepository;
        $this->fileUploader = $fileUploader;
        $this->formFactory = $formFactory;
        $this->getBook = $getBook;
        $this->getCategory = $getCategory;
        $this->createCategory = $createCategory;
    }

    public function __invoke(Request $request, ?string $bookId = null): array
    {
        $book = null;
        $bookDto = null;

        if ($bookId === null) {
            $book = Book::create();
            $bookDto = BookDto::createEmpty();
        } else {
            $book = ($this->getBook)($bookId);
            $bookDto = BookDto::createFromBook($book);
            foreach ($book->getCategories() as $category) {
                // We keep the original categories
                $bookDto->categories[] = CategoryDto::createFromCategory($category);
            }
        }
        // handleForm
        $form = $this->formFactory->create(BookFormType::class, $bookDto);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return [null, 'Form is not submitted'];
        }
        if (!$form->isValid()) {
            return [null, $form];
        }

        // Category loading.
        $categories = [];
        // Iteration of the categories sent by the user
        foreach ($bookDto->getCategories() as $newCategoryDto) {
            $category = null;
            if ($newCategoryDto->getId() !== null) {
                $category = ($this->getCategory)($newCategoryDto->getId());
            }
            if($category === null) {
                $category = ($this->createCategory)($newCategoryDto->getName());
            }
            $categories[] = $category;
        }

        $filename = null;
        if ($bookDto->getBase64Image()) {
            $filename = $this->fileUploader->uploadBase64File($bookDto->base64Image);
        }

        $book->update(
            $bookDto->getTitle(),
            $filename,
            $bookDto->getDescription(),
            Score::create($bookDto->getScore()),
            ...$categories
        );
        $this->bookRepository->save($book);
        return [$book, null];
    }
}