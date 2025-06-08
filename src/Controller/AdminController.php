<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\UserAdminType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    #[Route('/admin/users', name: 'admin_users')]
    public function users(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $users = $em->getRepository(User::class)->findAll();

        
        $forms = [];
        foreach ($users as $user) {
            $forms[$user->getId()] = $this->createForm(UserAdminType::class, $user, [
                'action' => $this->generateUrl('admin_user_edit', ['id' => $user->getId()])
            ])->createView();
        }

        return $this->render('admin/users.html.twig', [
            'users' => $users,
            'forms' => $forms,
        ]);
    }

    #[Route('/admin/user/{id}/edit', name: 'admin_user_edit', methods: ['POST'])]
    public function editUser(Request $request, User $user, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(UserAdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('admin_users');
        }

        return $this->redirectToRoute('admin_users');
    }

    #[Route('/admin/user/{id}/delete', name: 'admin_user_delete', methods: ['POST'])]
    public function deleteUser(User $user, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('admin_users');
    }
}