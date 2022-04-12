<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(PostRepository $postRepo, CategoryRepository $categoryRepo, UserRepository $userRepo ): Response
    {
        $categories = $categoryRepo->findAll();
        $authors = $userRepo->findAuthors();
        $posts = $postRepo->findBy([], ['createdAt' => 'DESC']);
        return $this->render('post/list.html.twig',[
            'title' => 'Accueil',
            'posts' => $posts,
            'authors' => $authors,
            'categories' => $categories
        ]);
    }

    #[Route('/search', name: 'search_bar')]
    public function search(PostRepository $postRepo): Response
    {
        $search = $_GET["search"] ?? '';
        $posts = $postRepo->findBySearch($search);
        return $this->render('post/list.html.twig', [
            'title' => 'Accueil',
            'posts' => $posts
        ]);

    }
}
