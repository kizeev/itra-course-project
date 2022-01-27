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

    #[Route('/user/collection/{id}/item/create', name: 'user_item_create')]
    public function create(Request $request, int $id): Response
    {
        $collection = $this->doctrine->getRepository(UserCollection::class)->find($id);
        $itemAttr = $collection->getAttribute();

        $item = new Item();
        $item->setUserCollection($collection);

        foreach ($itemAttr as $attribute) {
            $value = new Value();
            $item->getItemValues()->add($value);
            $value->setItem($item);
            $value->setAttribute($attribute);
        }

        if($collection->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(ItemFormType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->em->persist($item);
            $this->em->flush();
            return $this->redirect('/user/collection/'.$id);
        }

        return $this->render('user/create_item.html.twig', parameters: [
            'form' => $form->createView(),
            'title' => 'Create item',
        ]);
    }

    #[Route('user/item/{id}/edit', name: 'user_item_edit')]
    public function edit(int $id, Request $request): Response
    {
        $item = $this->doctrine->getRepository(Item::class)->find($id);
        $collection = $item->getUserCollection();
        $form = $this->createForm(ItemFormType::class, $item);
        $form->handleRequest($request);

        if($collection->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->em->flush();
            return $this->redirect('/user/collection/'.$collection->getId());
        }

        return $this->render('user/create_item.html.twig', parameters: [
            'title' => 'Edit item',
            'form' => $form->createView(),
        ]);
    }

    #[Route('user/item/{id}/remove', name: 'user_item_remove')]
    public function remove(int $id)
    {
        $item = $this->doctrine->getRepository(Item::class)->find($id);
        $collection = $item->getUserCollection();

        if($collection->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $this->em->remove($item);
        $this->em->flush();
        return $this->redirect('/user/collection/'.$collection->getId());
    }

    #[Route('user/value/create/{id}', name: 'user_value_create')]
    public function value(Request $request, $id)
    {

        $attrib = $this->doctrine->getRepository(Attribute::class)->find($id);

        $value = new Value();

        $form = $this->createForm(ValueFormType::class, $value, ['attrib' => $attrib->getFieldname()]);

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

    #[Route('user/item/{id}/show', name: 'user_item_show')]
    public function show(int $id): Response
    {
        $item = $this->doctrine->getRepository(Item::class)->find($id);
        $values = $item->getItemValues();

        return $this->render('user/show_item.html.twig', [
            'title' => $item->getName(),
            'values' => $values,
            'item' => $item
        ]);
    }
}
