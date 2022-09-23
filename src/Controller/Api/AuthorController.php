<?php 

namespace App\Controller\Api;

use App\Repository\AuthorRepository;
use App\Services\Author\AuthorFormProcessor;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/authors")
     * @Rest\View(serializerGroups={"author"}, serializerEnableMaxDepthChecks=true)
     */
    public function getActions(AuthorRepository $authorRepository)
    {
        return $authorRepository->findAll();
    }

    /**
     * @Rest\Post(path="/authors")
     * @Rest\View(serializerGroups={"author"}, serializerEnableMaxDepthChecks=true)
     */
    public function postActions(
        Request $request,
        AuthorFormProcessor $authorFormProcessor
    ) {
        [$author, $error] = ($authorFormProcessor)($request);
        $statusCode = $author ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $author ?? $error;
        return View::create($data, $statusCode);
    }
}