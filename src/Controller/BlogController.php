<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
    public function create(Request $request, EntityManagerInterface $doctrine)
    {
        $post = new Post();

        $form = $this->createFormBuilder($post)
                    ->add('title')
                    ->add('author', Author::class)
                    ->add('content')
                    ->add('image', TextType::class)
                    ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setCreatedAt(new \DateTimeImmutable());

            $doctrine->persist($post);
            $doctrine->flush();

            return $this->redirectToRoute('post', ['id' => $post->getId()]);
        }

        return $this->renderForm('blog/create.html.twig', [
            'title' => 'Poster',
            'postForm' => $form
        ]);
    }

    #[Route("/post/update/{id}", name: "post_update", requirements: ["id" => "\d+"])]
    public function update(int $id, PostRepository $postRepository, EntityManagerInterface $doctrine, Request $request)
    {
        $post = $postRepository->find($id);

        $form = $this->createFormBuilder($post)
        ->add('title')
        ->add('author')
        ->add('content')
        ->add('image', TextType::class)
        ->getForm();

        $form->handleRequest($request); 

        $doctrine->flush();

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUpdateAt(new \DateTime());

            $doctrine->persist($post);
            $doctrine->flush();

            return $this->redirectToRoute('post', ['id' => $post->getId()]);
        }

        return $this->renderForm('blog/create.html.twig', [
            'title' => 'Modifier',
            'postForm' => $form
        ]);
    }


    #[Route("/post/delete/{id}", name: "post_delete", requirements: ["id" => "\d+"])]
    
    public function delete($id, PostRepository $repo, EntityManagerInterface $doctrine)
    {
        $post = $repo->find($id);

        $doctrine->remove($post);

        $doctrine->flush();

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
