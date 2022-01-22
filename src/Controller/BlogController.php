<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(PostRepository $repository)
    {
        $posts = $repository->findAll();
        dump($posts);
        return $this->render('blog/home.html.twig',[
            'title' => 'Accueil',
            'posts' => $posts
        ]
    );
    }

    #[Route('article/{id}', name: 'article')]
    public function article($id, PostRepository $repository)
    {

        return $this->render('blog/article.html.twig', [
            'title' => 'article',
            'post' => $repository->find($id)
        ]);
    }
}
