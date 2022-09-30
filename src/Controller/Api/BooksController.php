<?php

namespace App\Controller\Api;

use App\Model\Book\BookRepositoryCriteria;
use App\Repository\BookRepository;
use App\Services\Book\BookFormProcessor;
use App\Services\Book\DeleteBook;
use App\Services\Book\GetBook;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\{Get, Post, Put, Patch, Delete};
use FOS\RestBundle\Controller\Annotations\View as ViewAttribute;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class BooksController extends AbstractFOSRestController
{
    #[Get(path: "/books")]
    #[ViewAttribute(serializerGroups: ['book'], serializerEnableMaxDepthChecks: true)]
    public function getActions(
        BookRepository $bookRepository,
        Request $request
    ) {
        $authorId = $request->query->get('authorId');
        $categoryId = $request->query->get('categoryId');
        $itemsPerPage = $request->query->get('itemsPerPage');
        $page = $request->query->get('page');

        $criteria = new BookRepositoryCriteria(
            $authorId,
            $categoryId,
            $itemsPerPage !== null ? intval($itemsPerPage) : 10,
            $page !== null ? intval($page) : 1,
        );

        return $bookRepository->findByCriteria($criteria);
    }

    #[Get(path: "/books/{id}")]
    #[ViewAttribute(serializerGroups: ['book'], serializerEnableMaxDepthChecks: true)]
    public function getSingleAction(
        string $id,
        GetBook $getBook
    ) {
        try {
            $book = ($getBook)($id);
        } catch (\Throwable) {
            return View::create('Book not found', Response::HTTP_BAD_REQUEST);
        }
        return $book;
    }

    #[Post(path: "/books")]
    #[ViewAttribute(serializerGroups: ['book'], serializerEnableMaxDepthChecks: true)]
    public function postAction(
        BookFormProcessor $bookFormProcessor,
        Request $request
    ) {
        [$book, $error] = ($bookFormProcessor)($request);
        $statusCode = $book ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $book ?? $error;
        return View::create($data, $statusCode);
    }

    #[Patch(path: "/books/{id}")]
    #[ViewAttribute(serializerGroups: ['book'], serializerEnableMaxDepthChecks: true)]
    public function patchAction(
        string $id,
        GetBook $getBook,
        Request $request
    ) {
        $book = ($getBook)($id);
        $data = json_decode($request->getContent(), true);
        $book->patch($data);
        return View::create($book, Response::HTTP_OK);
    }

    #[Put(path: "/books")]
    #[ViewAttribute(serializerGroups: ['book'], serializerEnableMaxDepthChecks: true)]
    public function editAction(
        Request $request,
        string $id,
        BookFormProcessor $bookFormProcessor
    ) {
        try {
            [$book, $error] = ($bookFormProcessor)($request, $id);
            $statusCode = $book ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
            $data = $book ?? $error;
            return View::create($data, $statusCode);
        } catch (Throwable) {
            return View::create('Book not found', Response::HTTP_BAD_REQUEST);
        }
    }

    #[Delete(path: "/books/{id}")]
    #[ViewAttribute(serializerGroups: ['book'], serializerEnableMaxDepthChecks: true)]
    public function deleteAction(
        string $id,
        DeleteBook $deleteBook
    ) {
        try {
            ($deleteBook)($id);
        } catch (Throwable){
            return View::create('Book not found', Response::HTTP_BAD_REQUEST);
        }
        return View::create('Book deleted', Response::HTTP_NO_CONTENT);
    }

}
