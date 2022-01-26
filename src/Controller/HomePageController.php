<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\UserCollection;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $doctrine
    )
    {}

    #[Route("/", name: "home")]
    public function welcome(): Response
    {
        $collections = $this->doctrine->getRepository(UserCollection::class)->findAll();
        $count = array();
        foreach ($collections as $collection) {
            $collection->getItem()->count();
            $count[$collection->getId()] = $collection->getAttribute()->count();
        }

        $items = $this->doctrine->getRepository(Item::class)->findBy(
            array(),
            array('id' => 'desc'),
            3
        );

        return $this->render("base.html.twig", [
            "title" => "HomePage",
            "title_body" => "All Collections",
            "collections" => $collections,
            'items' => $items
        ]);
    }

    #[Route("collections", "collections")]
    public function showAllCollections(): Response
    {
        $collections = $this->doctrine->getRepository(UserCollection::class)->findAll();
        return $this->render("base.html.twig", [
            'title' => 'HomePage',
            "title_body" => "All Collections",
            "collections" => $collections,
        ]);
    }
}