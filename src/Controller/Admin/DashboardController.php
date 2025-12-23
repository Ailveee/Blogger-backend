<?php
// src/Controller/Admin/DashboardController.php

namespace App\Controller\Admin;

use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'admin_dashboard')]
    public function index(ArticleRepository $articleRepo, UserRepository $userRepo, CategoryRepository $categoryRepo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $totalUsers = $userRepo->count([]);
        $totalArticles = $articleRepo->count([]);
        $totalCategories = $categoryRepo->count([]);
        $recentArticles = $articleRepo->findBy([], ['createdAt' => 'DESC'], 5);

        return $this->render('admin/dashboard.html.twig', [
            'totalUsers' => $totalUsers,
            'totalArticles' => $totalArticles,
            'totalCategories' => $totalCategories,
            'recentArticles' => $recentArticles
        ]);
    }
}
