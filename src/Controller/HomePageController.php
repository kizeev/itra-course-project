<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\UserCollection;
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
        $collections = $this->doctrine->getRepository(UserCollection::class)
            ->createQueryBuilder('c')
            ->select('c, COUNT(item.id) AS HIDDEN cCount')
            ->join('c.item', 'item')
            ->groupBy('item.userCollection')
            ->orderBy('cCount', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();

        $items = $this->doctrine->getRepository(Item::class)->findBy(
            array(),
            array('id' => 'DESC'),
            3
        );

        return $this->render("base.html.twig", [
            "title" => "HomePage",
            "title_body" => "All Collections",
            "collections" => $collections,
            'items' => $items
        ]);
    }

    #[Route("collections", name: "collections")]
    public function showCollections(): Response
    {
        $collections = $this->doctrine->getRepository(UserCollection::class)->findAll();
        return $this->render("read-only/collection.html.twig", [
            'title' => 'All Collections',
            "title_body" => "All Collections",
            "collections" => $collections,
        ]);
    }

    #[Route('/collection/{id}', name: 'collection_show')]
    public function showCollection(int $id): Response
    {
        $collection = $this->doctrine->getRepository(UserCollection::class)->find($id);
        $attributes = $collection->getAttribute();

        return $this->render('read-only/show_collection.html.twig', [
            'title' => $collection->getName(),
            'items' => $collection->getItem(),
            'attributes' => $attributes,
        ]);
    }

    #[Route('item/{id}/show', name: 'item_show')]
    public function showItem(int $id): Response
    {
        $item = $this->doctrine->getRepository(Item::class)->find($id);
        $values = $item->getItemValues();

        return $this->render('read-only/show_item.html.twig', [
            'title' => $item->getName(),
            'values' => $values,
            'item' => $item
        ]);
    }

    #[Route('items', name: 'items')]
    public function showItems(): Response
    {
        $items = $this->doctrine->getRepository(Item::class)->findAll();

        return $this->render('read-only/item.html.twig', [
            'title' => 'All Items',
            'title_body' => 'All Items',
            'items' => $items
        ]);
    }
}