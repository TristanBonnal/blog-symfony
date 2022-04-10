<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(PostRepository $repository): Response
    {
        $posts = $repository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('post/list.html.twig',[
            'title' => 'Accueil',
            'posts' => $posts
        ]);
    }

    #[Route('/search', name: 'search_bar')]
    public function search(PostRepository $repository, Request $request): Response
    {
        $search = $_GET["search"];
        $posts = $repository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('post/list.html.twig', [
            'title' => 'Accueil',
            'posts' => $posts
        ]);

    }
}
