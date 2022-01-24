<?php

namespace App\Controller\User;

use App\Entity\Attribute;
use App\Entity\Item;
use App\Entity\UserCollection;
use App\Entity\Value;
use App\Form\ItemFormType;
use App\Form\ValueFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
        $attributes = $collection->getAttribute();

        foreach ($attributes as $attribute) {
            $value = new Value();
            $value->setValue('');
            $item->getItemValues()->add($value);
            $value->setItem($item);
            $value->setAttribute($attribute);
            $lab = $value->getAttribute();
        }

        $form = $this->createForm(ItemFormType::class, $item, options: array('attributes' => $attributes));
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

    #[Route('user/item/remove/{id}', name: 'user_item_remove')]
    public function remove(int $id)
    {
        $item = $this->doctrine->getRepository(Item::class)->find($id);
        $collection = $item->getUserCollection();
        $collection_id = $collection->getId();
        $this->em->remove($item);
        $this->em->flush();
        return $this->redirect('/user/collection/'.$collection_id);
    }

    #[Route('user/value/create', name: 'user_value_create')]
    public function value(Request $request)
    {

        $value = new Value();

        $form = $this->createForm(ValueFormType::class, $value);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->em->persist($value);
            $this->em->flush();
            return $this->redirectToRoute('user_item');
        }

        return $this->render('user/create_value.html.twig', parameters: [
            'title' => 'Value',
            'form' => $form->createView()
        ]);
    }
}