<?php

namespace App\Controller\User;

use App\Entity\UserCollection;
use App\Form\UserCollectionFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
class UserCollectionController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private ManagerRegistry $doctrine
    )
    {}

    #[Route('/user/collection', name: 'user_collection')]
    public function index(UserInterface $user): Response
    {
        $collections = $this->doctrine->getRepository(UserCollection::class)->findBy(array('user' => $user));
        return $this->render('user/collection.html.twig', [
            'collections' => $collections,
            'title' => 'My collections',
        ]);
    }

    #[Route('/user/collection/create', name: 'user_collection_create')]
    public function create(Request $request, UserInterface $user)
    {
        $collection = new UserCollection();

        $form = $this->createForm(type: UserCollectionFormType::class, data: $collection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $collection->setUser($user);
            $this->em->persist($collection);
            $this->em->flush();
            return $this->redirectToRoute('user_collection');
        }

        return $this->render('user/create_collection.html.twig', parameters: [
        'form' => $form->createView(),
        'title' => 'Create collection',
    ]);
    }

    #[Route('/user/collection/{id}', name: 'user_collection_show')]
    public function show(int $id): Response
    {
        $collection = $this->doctrine->getRepository(UserCollection::class)->find($id);
        $attributes = $collection->getAttribute();
        return $this->render('user/show_collection.html.twig', [
            'collection' => $collection,
            'title' => 'My collection: '.$collection->getName(),
            'items' => $collection->getItem(),
            'attributes' => $attributes,
        ]);
    }

    #[Route('user/collection/edit/{id}', name: 'user_collection_edit')]
    public function edit(int $id, Request $request): Response
    {
        $collection = $this->doctrine->getRepository(UserCollection::class)->find($id);
        $form = $this->createForm(UserCollectionFormType::class, $collection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->em->flush();
            return $this->redirectToRoute('user_collection');
        }

        return $this->render('user/create_collection.html.twig', parameters: [
            'title' => 'Edit collection',
            'form' => $form->createView(),
        ]);
    }

    #[Route('user/collection/remove/{id}', name: 'user_collection_remove')]
    public function remove(int $id)
    {
        $collection = $this->doctrine->getRepository(UserCollection::class)->find($id);
        $this->em->remove($collection);
        $this->em->flush();
        return $this->redirectToRoute('user_collection');
    }
}
