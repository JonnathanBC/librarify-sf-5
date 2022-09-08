<?php

namespace App\Services\Book;

use App\Entity\Book;
use App\Entity\Book\Score;
use App\Form\Model\BookDto;
use App\Form\Model\CategoryDto;
use App\Form\Type\BookFormType;
use App\Repository\BookRepository;
use App\Services\Author\CreateAuthor;
use App\Services\Author\GetAuthor;
use App\Services\Category\CreateCategory;
use App\Services\Category\GetCategory;
use App\Services\FileUploader;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class BookFormProcessor
{
    private BookRepository $bookRepository;
    private FileUploader $fileUploader;
    private FormFactoryInterface $formFactory;
    private GetBook $getBook;
    private GetCategory $getCategory;
    private CreateCategory $createCategory;
    private GetAuthor $getAuthor;
    private CreateAuthor $createAuthor;
    private EventDispatcherInterface $eventDispatcher;
    
    public function __construct(
        BookRepository $bookRepository,
        FileUploader $fileUploader,
        FormFactoryInterface $formFactory,
        GetBook $getBook,
        GetCategory $getCategory,
        CreateCategory $createCategory,
        GetAuthor $getAuthor,
        CreateAuthor $createAuthor,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->bookRepository = $bookRepository;
        $this->fileUploader = $fileUploader;
        $this->formFactory = $formFactory;
        $this->getBook = $getBook;
        $this->getAuthor = $getAuthor;
        $this->getCategory = $getCategory;
        $this->createAuthor = $createAuthor;
        $this->createCategory = $createCategory;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(Request $request, ?string $bookId = null): array
    {
        $book = null;
        $bookDto = null;

        if ($bookId === null) {
            $bookDto = BookDto::createEmpty();
        } else {
            $book = ($this->getBook)($bookId);
            $bookDto =  BookDto::createFromBook($book);
            foreach ($book->getCategories() as $category) {
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
        foreach ($bookDto->categories as $newCategoryDto) {
            $category = null;
            if ($newCategoryDto->getId() !== null) {
                $category = ($this->getCategory)($newCategoryDto->getId());
            }
            if($category === null) {
                $category = ($this->createCategory)($newCategoryDto->getName());
            }
            $categories[] = $category;
        }

        $authors = [];
        foreach ($bookDto->authors as $newAuthorDto) {
            $author = null;
            if ($newAuthorDto->id !== null) {
                $author = ($this->getAuthor)($newAuthorDto->id);
            }
            if ($author === null) {
                $author = ($this->createAuthor)($newAuthorDto->name);
            }
            $authors[] = $author;
        }

        $filename = null;
        if ($bookDto->base64Image) {
            $filename = $this->fileUploader->uploadBase64File($bookDto->base64Image);
        }

        if ($book === null) {
            $book = Book::create(
                $bookDto->title,
                $filename,
                $bookDto->description,
                Score::create($bookDto->score),
                $bookDto->readAt,
                $authors,
                $categories
            );
        } else {
            $book->update(
                $bookDto->title,
                $filename === null ? $book->getImage() : $filename,
                $bookDto->description,
                Score::create($bookDto->score),
                $bookDto->readAt,
                $authors,
                $categories
            );
        }
        $this->bookRepository->save($book);
        foreach ($book->pullDomainEvent() as $event) {
            $this->eventDispatcher->dispatch($event);
        }
        return [$book, null];
    }
}