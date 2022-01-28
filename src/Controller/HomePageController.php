<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Tag;
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

        $tags = $this->doctrine->getRepository(Tag::class)->findAll();

        return $this->render("base.html.twig", [
            "title" => "HomePage",
            "title_body" => "All Collections",
            "collections" => $collections,
            'items' => $items,
            'tags' => $tags
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
        $tags = $item->getTags();

        return $this->render('read-only/show_item.html.twig', [
            'title' => $item->getName(),
            'values' => $values,
            'item' => $item,
            'tags' => $tags
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

    #[Route('tag/{id}', name: 'tag')]
    public function snowItemsByTag(int $id): Response
    {
        $tag = $this->doctrine->getRepository(Tag::class)->findOneBy(['id' => $id]);
        $items = $tag->getItem();

        return $this->render('read-only/item.html.twig', [
            'title' => 'Items by tag',
            'items' => $items
        ]);
    }
}