<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminUserPhotoType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[IsGranted('ROLE_ADMIN')]
class AdminUserController extends AbstractController
{
    #[Route('/admin/user/{id}/delete', name: 'admin_user_delete', methods: ['POST'])]
    public function delete(User $user, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete-user-' . $user->getId(), $request->request->get('_token'))) {
            $em->remove($user);
            $em->flush();
            $this->addFlash('success', 'Utilisateur supprimé.');
        }
        return $this->redirectToRoute('app_home_page');
    }
    #[Route('/admin/user/{id}/edit', name: 'admin_user_edit', methods: ['POST'])]
    public function modify(User $user, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        if ($this->isCsrfTokenValid('edit-user-' . $user->getId(), $request->request->get('_token'))) {
            $form = $this->createForm(RegistrationFormType::class, $user, ['is_edit' => true]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $plainPassword = $form->get('plainPassword')->getData();
                if ($plainPassword) {
                    $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
                }
                $em->flush();
                $this->addFlash('success', 'Profil mis à jour.');
                return $this->redirectToRoute('app_profile');
            }

            return $this->render('profile/edit.html.twig', [
                'profileForm' => $form->createView(),
                'user' => $user,
            ]);
        }
        return $this->redirectToRoute('app_home_page');
    }
    #[Route('/admin/user/{id}/photo', name: 'admin_user_photo', methods: ['POST'])]
    public function uploadPhoto(User $user, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AdminUserPhotoType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('photo')->getData();
            if ($photoFile) {
                $newFilename = uniqid().'.'.$photoFile->guessExtension();
                try {
                    $photoFile->move(
                        $this->getParameter('kernel.project_dir').'/public/img',
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Erreur lors de l\'upload.');
                    return $this->redirectToRoute('app_home_page');
                }
                $user->setPhoto('img/'.$newFilename);
                $em->flush();
                $this->addFlash('success', 'Photo mise à jour.');
            }
        }
        return $this->redirectToRoute('app_home_page');
    }

}


    // #[Route('/profile/edit', name: 'app_profile_edit')]
    // public function edit(Request $request, EntityManagerInterface $em, \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface $passwordHasher): Response
