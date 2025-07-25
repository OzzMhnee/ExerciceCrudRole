<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
final class DashBordAdminController extends AbstractController
{
    #[Route('/dashbordadmin', name: 'app_dash_bord_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('dash_bord_admin/index.html.twig', [
            'controller_name' => 'DashBordAdminController',
        ]);
    }
}
