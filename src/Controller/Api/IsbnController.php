<?php

namespace App\Controller\Api;

use App\Services\Isbn\GetBookByIsbn;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View as ViewAttribute;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IsbnController extends AbstractFOSRestController
{
    #[Get("/isbn")]
    #[ViewAttribute(serializerGroups: ['book_isbn'], serializerEnableMaxDepthChecks: true)]
    public function getAction(GetBookByIsbn $getBookByIsbn, Request $request): View
    {
        $isbn = $request->get('isbn', null);
        if ($isbn === null) {
            return View::create('Please, specify an isbn', Response::HTTP_BAD_REQUEST);
        }
        $json = ($getBookByIsbn)($isbn);
        return View::create($json);
    }
}
