<?php 

namespace App\Controller\Api;

use App\Repository\AuthorRepository;
use App\Services\Author\AuthorFormProcessor;
use App\Services\Author\DeleteAuthor;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\{Delete, Get, Post};
use FOS\RestBundle\Controller\Annotations\View as ViewAttribute;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthorController extends AbstractFOSRestController
{
    #[Get(path: "/authors")]
    #[ViewAttribute(serializerGroups: ["author"], serializerEnableMaxDepthChecks: true)]
    public function getActions(AuthorRepository $authorRepository)
    {
        return $authorRepository->findAll();
    }

    #[Post(path: "/authors")]
    #[ViewAttribute(serializerGroups: ["author"], serializerEnableMaxDepthChecks: true)]
    public function postActions(
        Request $request,
        AuthorFormProcessor $authorFormProcessor
    ) {
        [$author, $error] = ($authorFormProcessor)($request);
        $statusCode = $author ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $author ?? $error;
        return View::create($data, $statusCode);
    }

    #[Delete("/authors/{id}")]
    #[ViewAttribute(serializerGroups: ["author"], serializerEnableMaxDepthChecks: true)]
    public function deleteActions(
        string $id,
        DeleteAuthor $deleteAuthor
    ) {
        try {
            ($deleteAuthor)($id);
        } catch (Throwable) {
            View::create("Author not found", Response::HTTP_BAD_REQUEST);
        }
        return View::create('Author deleted', Response::HTTP_NO_CONTENT);
    }
}