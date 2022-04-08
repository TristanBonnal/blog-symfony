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
        return $this->render('post/list.html.twig',[
            'title' => 'Accueil',
            'posts' => $posts
        ]);
    }

    #[Route('/', name: 'search_bar')]
    public function search(PostRepository $repository): Response
    {
        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        $posts = $repository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('post/list.html.twig',[
            'title' => 'Accueil',
            'posts' => $posts
        ]);
    }
}
