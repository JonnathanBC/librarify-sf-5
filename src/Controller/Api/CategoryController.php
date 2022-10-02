<?php 

namespace App\Controller\Api;

use App\Repository\CategoryRepository;
use App\Services\Category\CategoryFormProcessor;
use App\Services\Category\DeleteCategory;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\{Delete, Get, Post};
use FOS\RestBundle\Controller\Annotations\View as ViewAttribute;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CategoryController extends AbstractFOSRestController
{
    #[Get("/categories")]
    #[ViewAttribute(serializerGroups: ["category"], serializerEnableMaxDepthChecks: true)]
    public function getActions(CategoryRepository $categoryRepository)
    {
        return $categoryRepository->findAll();
    }

    #[Post("/categories")]
    #[ViewAttribute(serializerGroups: ["category"], serializerEnableMaxDepthChecks: true)]
    public function postActions(
        Request $request,
        CategoryFormProcessor $categoryFormProcessor
    ) {
        [$category, $error] = ($categoryFormProcessor)($request);
        $statusCode = $category ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $category ?? $error;
        return View::create($data, $statusCode);
    }
    
    #[Delete(path: "/categories/{id}")]
    #[ViewAttribute(serializerGroups: ['category'], serializerEnableMaxDepthChecks: true)]
    public function deleteAction(
        string $id,
        DeleteCategory $deleteCategory
    ) {
        try {
            ($deleteCategory)($id);
        } catch (Throwable){
            return View::create('Category not found', Response::HTTP_BAD_REQUEST);
        }
        return View::create('Category deleted', Response::HTTP_NO_CONTENT);
    }
}