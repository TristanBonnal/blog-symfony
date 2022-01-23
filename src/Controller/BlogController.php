<?php

namespace App\Controller;

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
                    ->add('author')
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

    /**
     * Post update
     * 
     * @Route("/post/update/{id}", requirements={"id"="\d+"})
     */
    public function update(int $id, PostRepository $postRepository, EntityManagerInterface $doctrine)
    {
        $post = $postRepository->find($id);

        $post->setTitle('Avatar ' . mt_rand(2, 99));

        $doctrine->flush();

        return $this->redirectToRoute("posts");
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

}
