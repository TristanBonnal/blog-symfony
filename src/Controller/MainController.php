<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(PostRepository $repository): Response
    {
        $posts = $repository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('main/home.html.twig',[
            'title' => 'Accueil',
            'posts' => $posts
        ]
    );
    }
}
