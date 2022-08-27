<?php 

namespace App\Controller\Api;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Services\CategoryManager;

class CategoryController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/categories")
     * @Rest\View(serializerGroups={"category"}, serializerEnableMaxDepthChecks=true)
     */
    public function getActions(CategoryManager $categoryManager)
    {
        return $categoryManager->getRepository()->findAll();
    }
}