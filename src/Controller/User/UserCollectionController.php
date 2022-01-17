<?php

namespace App\Controller\User;

use App\Entity\UserCollection;
use App\Form\UserCollectionFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
class UserCollectionController extends AbstractController
{
    #[Route('/user/collection', name: 'user_collection')]
    public function index(UserInterface $user): Response
    {
        $collections = $user->getUserCollections();
        return $this->render('user/collection.html.twig', [
            'controller_name' => 'UserCollectionController',
            'collections' => $collections,
        ]);
    }

    #[Route('/user/collection/create', name: 'user_collection_create')]
    public function create(ManagerRegistry $doctrine, Request $request, UserInterface $user): Response
    {
        $collection = new UserCollection();
        $form = $this->createForm(type: UserCollectionFormType::class, data: $collection);
        $entityManager = $doctrine->getManager();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $collection->setUser($user);
            $entityManager->persist($collection);
            $entityManager->flush();
            return $this->redirectToRoute('user_collection');
        }

        return $this->render('user/create_collection.html.twig', parameters: [
        'form' => $form->createView(),
        'title' => 'Create collection',
    ]);
    }
}
