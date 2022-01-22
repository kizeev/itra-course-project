<?php

namespace App\Controller\User;

use App\Entity\Item;
use App\Entity\UserCollection;
use App\Form\ItemFormType;
use App\Form\UserCollectionFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class UserItemController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private ManagerRegistry $doctrine,
    )
    {}

    #[Route('/user/item', name: 'user_item')]
    public function index(UserInterface $user): Response
    {
        $collections = $this->doctrine->getRepository(UserCollection::class)->findBy(array('user' => $user));
        $items = $this->doctrine->getRepository(Item::class)->findBy(array('userCollection' => $collections));
        return $this->render('user/item.html.twig', [
            'items' => $items,
            'title' => 'My Items'
        ]);
    }

    #[Route('/user/item/show/{id}', name: 'user_item_show')]
    public function show(int $id): Response
    {
        $item = $this->doctrine->getRepository(Item::class)->find($id);
        return $this->render('user/show_item.html.twig', [
            'item' => $item,
            'title' => 'My item: '.$item->getName(),
        ]);
    }

    #[Route('/user/collection/{id}/item/create', name: 'user_item_create')]
    public function create(Request $request, int $id): Response
    {
        $item = new Item();
        $collection = $this->doctrine->getRepository(UserCollection::class)->find($id);
        $form = $this->createForm(ItemFormType::class, data: $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $item->setUserCollection($collection);
            $this->em->persist($item);
            $this->em->flush();
            return $this->redirect('/user/collection/'.$id);
        }

        return $this->render('user/create_item.html.twig', parameters: [
            'form' => $form->createView(),
            'title' => 'Create item'
        ]);
    }

    #[Route('user/item/edit/{id}', name: 'user_item_edit')]
    public function edit(int $id, Request $request): Response
    {
        $item = $this->doctrine->getRepository(Item::class)->find($id);
        $form = $this->createForm(ItemFormType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->em->flush();
            return $this->redirect('/user/item/show/'.$id);
        }

        return $this->render('user/create_item.html.twig', parameters: [
            'title' => 'Edit item',
            'form' => $form->createView(),
        ]);
    }
}
