<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('post/new', name: 'post_new')]
    #[Route('post/update/{id}', name: 'post_update')]
    public function save(Request $request, EntityManagerInterface $manager, Post $post = null)
    {
        if (!isset($post)) {
            $post = new Post();
        }

        //Création formulaire
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        //Redirection après validation du form
        if ($form->isSubmitted() && $form->isValid()) {
            if ($post->getId()) {
                $post->setUpdateAt(new \DateTimeImmutable);
            } else {
                $post->setCreatedAt(new \DateTimeImmutable());
            }
            $manager->persist($post);
            $manager->flush();

            return $this->redirectToRoute('post', ['id' => $post->getId()]);
        }

        //Render form (pas de données post)
        return $this->renderForm('blog/create.html.twig', [
            'title' => 'Poster',
            'postForm' => $form,
            'updateForm' => $post->getId() != null //Si pas d'id, permet de modifier l'affichage twig pour un form d'update
        ]);
    }


    #[Route("/post/delete/{id}", name: "post_delete", requirements: ["id" => "\d+"])]
    public function delete($id, PostRepository $repo, EntityManagerInterface $manager)
    {
        $post = $repo->find($id);

        $manager->remove($post);

        $manager->flush();

        return $this->redirectToRoute("home");
    }


    #[Route('post/{id}', name: 'post', requirements: ['page' => '\d+'])]
    public function post(Post $post)
    {
        return $this->render('blog/post.html.twig', [
            'title' => 'article',
            'post' => $post
        ]);
    }


    #[Route('/author/list/{id}', name: 'authors_posts')]
    public function postsByAuthor(Author $author)
    {
        return $this->render('blog/home.html.twig',[
            'title' => 'Accueil',
            'posts' => $author->getPosts()
        ]
    );
    }

}
