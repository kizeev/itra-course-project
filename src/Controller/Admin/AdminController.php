<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\UserCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'title' => 'Admin',
        ]);
    }

    #[Route('/admin/collection', name: 'admin_collection')]
    public function getCollections(ManagerRegistry $doctrine): Response
    {
        $collections = $doctrine->getRepository(UserCollection::class)->findAll();
        return $this->render('admin/collections.html.twig', [
            'title' => 'Collections',
            'collections' => $collections
        ]);
    }
}
