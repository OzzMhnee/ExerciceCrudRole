<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\AdminUserPhotoType;
use App\Form\UserFilterType;
use App\Form\RegistrationFormType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;

#[Route('/admin')]


final class DashBordAdminController extends AbstractController
{
    #[Route('/dashbordadmin', name: 'app_dash_bord_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $users = [];
        $form_admin_user_photo = [];
        $filterForm = $this->createForm(UserFilterType::class, null, ['method' => 'GET']);
        $filterForm->handleRequest($request);

        $repo = $em->getRepository(User::class);
        $qb = $repo->createQueryBuilder('u');

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $filterData = $filterForm->getData();
            if (!empty($filterData['nom'])) {
                $qb->andWhere('u.nom LIKE :nom')->setParameter('nom', '%'.$filterData['nom'].'%');
            }
            if (!empty($filterData['prenom'])) {
                $qb->andWhere('u.prenom LIKE :prenom')->setParameter('prenom', '%'.$filterData['prenom'].'%');
            }
            if (!empty($filterData['email'])) {
                $qb->andWhere('u.email LIKE :email')->setParameter('email', '%'.$filterData['email'].'%');
            }
            if (!empty($filterData['role'])) {
                $qb->andWhere('u.roles LIKE :role')->setParameter('role', '%'.$filterData['role'].'%');
            }
            if (!empty($filterData['sans_photo'])) {
                $qb->andWhere('u.photo IS NULL OR u.photo = \'\'');
            }
        }

        $users = $qb->getQuery()->getResult();

        foreach ($users as $user) {
            $form_admin_user_photo[$user->getId()] = $this->createForm(AdminUserPhotoType::class)->createView();
        }

        return $this->render('dashbord_admin/trombinoscope.html.twig', [
            'users' => $users,
            'form_admin_user_photo' => $form_admin_user_photo,
            'filterForm' => $filterForm->createView(),
        ]);
    }
    #[Route('/admin/user/{id}/delete', name: 'admin_user_delete', methods: ['POST'])]
    public function delete(User $user, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete-user-' . $user->getId(), $request->request->get('_token'))) {
            $em->remove($user);
            $em->flush();
            $this->addFlash('success', 'Utilisateur supprimé.');
        }
        return $this->redirectToRoute('app_dash_bord_admin');
    }

    #[Route('/admin/user/{id}/edit', name: 'admin_user_edit')]
    public function modify(User $user, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(RegistrationFormType::class, $user, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
            }
            $em->flush();
            $this->addFlash('success', 'Profil mis à jour.');
            return $this->redirectToRoute('app_dash_bord_admin');
        }

        return $this->render('profile/edit.html.twig', [
            'profileForm' => $form->createView(),
            'user' => $user,
        ]);
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
                    return $this->redirectToRoute('app_dash_bord_admin');
                }
                $user->setPhoto('img/'.$newFilename);
                $em->flush();
                $this->addFlash('success', 'Photo mise à jour.');
            }
        }
        return $this->redirectToRoute('app_dash_bord_admin');
    }
}