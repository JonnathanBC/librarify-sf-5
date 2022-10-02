<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CacheClearController extends AbstractController
{
    #[Route('/admin/cache', name: 'admin_cache_clear')]
    public function __invoke(): Response
    {
        return new Response('Here goes the cache cleaning');
    }
}

