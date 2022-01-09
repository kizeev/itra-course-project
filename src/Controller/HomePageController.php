<?php

namespace App\Controller;

use App\Entity\UserCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    #[Route("/", name: "home")]
    public function welcome(ManagerRegistry $doctrine): Response
    {
        $collections = $doctrine->getRepository(UserCollection::class)->findAll();
        return $this->render("base.html.twig", [
            "title" => "HomePage",
            "title_body" => "All Collections",
            "collections" => $collections,
        ]);
    }
}