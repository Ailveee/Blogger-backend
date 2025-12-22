<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(ArticleRepository $articleRepository): Response
    {
        // fetch all published articles (optional: only published)
        $articles = $articleRepository->findBy(['status' => 'published'], ['createdAt' => 'DESC']);

        if ($this->getUser()) {
            return $this->render('home/index.html.twig', [
                'articles' => $articles, // pass articles to the template
            ]);
        }

        // Otherwise, redirect to login
        return $this->redirectToRoute('app_login');
    }
}
