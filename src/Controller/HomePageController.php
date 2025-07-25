<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\AdminUserPhotoType;
use App\Form\UserFilterType;
use Symfony\Component\HttpFoundation\Request;

final class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_home_page')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $users = [];
        $form_admin_user_photo = [];
        $filterData = [];
        $filterForm = $this->createForm(UserFilterType::class, null, ['method' => 'GET']);

        $filterForm->handleRequest($request);

        if ($this->isGranted('ROLE_ADMIN')) {
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
        }

        return $this->render('home_page/index.html.twig', [
            'controller_name' => 'HomePageController',
            'users' => $users,
            'form_admin_user_photo' => $form_admin_user_photo,
            'filterForm' => $filterForm->createView(),
        ]);
    }
}
