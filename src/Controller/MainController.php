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
    public function home(PostRepository $postRepo): Response
    {
        $posts = $postRepo->findBy([], ['createdAt' => 'DESC']);
        return $this->render('post/list.html.twig',[
            'title' => 'Accueil',
            'posts' => $posts,
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

    public function aside(CategoryRepository $categoryRepo, UserRepository $userRepo): Response
    {
        $categories = $categoryRepo->findAll();
        $authors = $userRepo->findAuthors();

        // Récupération de la route et de l'id du lien cliqué, permet dans le template d'afficher dynamiquement la class "active"
        $route = $this->container->get('request_stack')->getMasterRequest()->get('_route');
        $route_params = $this->container->get('request_stack')->getMasterRequest()->get('_route_params');
        if (!empty($route_params)) $route_params = $route_params['id'];

        return $this->render('_aside.html.twig', [
            'authors' => $authors,
            'categories' => $categories,
            'route' => $route,
            'route_params' => $route_params
        ]);
    }
}
