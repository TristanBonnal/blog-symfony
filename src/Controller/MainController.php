<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{
    /**
     * Page d'accueil listant tous les articles paginés
     *
     * @param PostRepository $postRepo
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/', name: 'home')]
    public function home(PostRepository $postRepo, PaginatorInterface $paginator, Request $request): Response
    {
        $posts = $postRepo->findBy([], ['createdAt' => 'DESC']);

        // Pagination faite avec le bundle KnpLabs/KnpPaginator
        $orderedPosts = $paginator->paginate(
            $posts,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('post/list.html.twig',[
            'title' => 'Accueil',
            'posts' => $orderedPosts,
        ]);
    }

    /**
     * Affiche les résultats d'une recherche de titre d'article ou d'auteur
     *
     * @param PostRepository $postRepo
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/search', name: 'search_bar')]
    public function search(PostRepository $postRepo, PaginatorInterface $paginator, Request $request): Response
    {
        $search = $_GET["search"] ?? '';
        $posts = $postRepo->findBySearch($search);

        // Pagination faite avec le bundle KnpLabs/KnpPaginator
        $orderedPosts = $paginator->paginate(
            $posts,
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('post/list.html.twig', [
            'title' => 'Accueil',
            'posts' => $orderedPosts
        ]);
    }

    /**
     * Méthode permettant le chargement dynamique des catégories et des auteurs dans le template aside quelque soit la route demandée
     *
     * @param CategoryRepository $categoryRepo
     * @param UserRepository $userRepo
     * @return Response
     */
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
