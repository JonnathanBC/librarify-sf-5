<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryAdminController extends CRUDController
{
    public function cloneAction($id): Response
    {
        /** @var Category */
        $object = $this->admin->getSubject();
        if (!$object) {
            throw new NotFoundHttpException(sprintf('Unable to find the object with id: %s', $id));
        }

        $clonedCategory = Category::create($object->getName());
        $this->admin->create($clonedCategory);

        $this->addFlash('sonata_flash_success', 'Cloned successfully');

        return new RedirectResponse($this->admin->generateUrl('list'));
    }

    public function importAction(
        Request $request
    ): Response
    {
        return new Response('This is an import function');
    }
}
