<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
class AdminUserController extends AbstractController
{

    /**
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @param UserPasswordHasherInterface $passwordHasher
     * @return Response
     */
    #[Route("/admin/create/", name: "create_user")]
    public function createUser(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(type: RegistrationFormType::class, data: $user);
        $entityManager = $doctrine->getManager();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $hashedPassword = $passwordHasher->hashPassword($user, plainPassword: $user->getPassword());
            $user->setPassword($hashedPassword);
            $user->setRoles(roles: ['ROLE_ADMIN']);
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute(route: 'home');
        }

        return $this->render(view: 'admin/createUser.html.twig', parameters: [
            'form' => $form->createView()
        ]);
    }
}