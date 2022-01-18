<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\AdminUserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private \Doctrine\Persistence\ManagerRegistry $doctrine
    )
    {}

    #[Route('/admin/user', name: 'admin_user')]
    public function index(): Response
    {
        $users = $this->doctrine->getRepository(User::class)->findAll();
        return $this->render('admin/users.html.twig', [
            'title' => 'Users',
            'users' => $users,
        ]);
    }

    #[Route('admin/user/show/{id}', name: 'admin_user_show')]
    public function show(int $id, Request $request): Response
    {
        $user = $this->doctrine->getRepository(User::class)->find($id);
        return $this->render('admin/show_user.html.twig', [
            'user' => $user,
            'title' => $user->getName().' collections',
            'collections' => $user->getUserCollections(),
            ]);
    }

    #[Route('admin/user/edit/{id}', name: 'admin_user_edit')]
    public function editUser(int $id, Request $request): Response
    {
        $user = $this->doctrine->getRepository(User::class)->find($id);
        $form = $this->createForm(AdminUserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->em->flush();
            return $this->redirectToRoute('admin_user');
        }
        return $this->render('admin/edit_user.html.twig', parameters: [
            'title' => 'Edit user',
            'form' => $form->createView(),
        ]);
    }

    #[Route('admin/user/remove/{id}', name: 'admin_user_remove')]
    public function removeUser(int $id, Request $request): Response
    {
        $user = $this->doctrine->getRepository(User::class)->find($id);
        $this->em->remove($user);
        $this->em->flush();
        return $this->redirectToRoute('admin_user');
    }

    #[Route('admin/user/switch/{id}', name: 'admin_user_switch')]
    public function switchUser(int $id, Request $request): Response
    {
        $user = $this->doctrine->getRepository(User::class)->find($id);
        $url = $request->getSchemeAndHttpHost();
        return $this->redirect($url.'/?_switch_user='.$user->getEmail());

    }
}
