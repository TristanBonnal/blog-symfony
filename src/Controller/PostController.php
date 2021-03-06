<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class PostController extends AbstractController
{

    /**
     * Affiche un article et permet aux utilisateurs loggés de commenter
     */
    #[Route('post/{id}', name: 'post', requirements: ['id' => '\d+'])]
    public function show(Post $post, Request $request, EntityManagerInterface $manager): Response
    {
        $comment = new Comment;
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Vérifie qu'un utilisateur est connecté pour pouvoir commenter
            try {
                $this->denyAccessUnlessGranted('COMMENT', $this->getUser());
            } catch (\Exception $e) {
                return $this->redirectToRoute('app_login');
            }
            // Persistence en bdd
            $comment->setCreatedAt(new \DateTime());
            $comment->setUser($this->getUser());
            $post->addComment($comment);
            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('post', ['id' => $post->getId()]);
        }

        return $this->renderForm('post/post.html.twig', [
            'title' => 'Article n°' . $post->getId(),
            'post' => $post,
            'commentForm' => $form
        ]);

    }

    /**
     * Permet l'ajout d'un article si l'utilisateur loggé est moderateur ou admin
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('post/new', name: 'post_new')]
    #[IsGranted('ROLE_MODERATOR')]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        //Création formulaire
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        //Redirection après validation du form
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUser($this->getUser());
            $manager->persist($post);
            $manager->flush();

            return $this->redirectToRoute('post', ['id' => $post->getId()]);
        }

        //Render form (pas de données post)
        return $this->renderForm('post/form.html.twig', [
            'title' => 'Nouvel article',
            'postForm' => $form,
            'updateForm' => false // Permet d'utiliser un seul form (meme template) pour la création et l'edition de post
        ]);
    }

    /**
     * Modification d'un post à condition d'être un l'auteur du billet, ou admin
     */
    #[Route('post/update/{id}', name: 'post_update')]
    #[IsGranted('ROLE_MODERATOR')]
    public function update(Request $request, EntityManagerInterface $manager, Post $post = null): Response
    {
        if (!$post) {
            throw $this->createNotFoundException('Cet article n\'existe pas');
        }

        $this->denyAccessUnlessGranted('POST_EDIT', $post, 'Accès refusé');

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($post->getId()) {
                $post->setUpdateAt(new \DateTime);
            } else {
                $post->setCreatedAt(new \DateTime());
            }
            $manager->persist($post);
            $manager->flush();

            return $this->redirectToRoute('post', ['id' => $post->getId()]);
        }

        return $this->renderForm('post/form.html.twig', [
            'title' => 'Nouvel article',
            'postForm' => $form,
            'updateForm' => true
            ]);
    }

    /**
     * Suppression d'un article
     */
    #[Route("/post/delete/{id}", name: "post_delete", requirements: ["id" => "\d+"])]
    #[IsGranted('ROLE_MODERATOR')]
    public function delete($id, PostRepository $repo, EntityManagerInterface $manager): Response
    {
        $post = $repo->find($id);
        $this->denyAccessUnlessGranted('POST_DELETE', $post, 'Accès refusé');
        $manager->remove($post);
        $manager->flush();
        return $this->redirectToRoute("home");
    }

    /**
     * Articles triés par utilisateur sélectionné
     */
    #[Route('/user/list/{id}', name: 'users_posts')]
    public function postsByUser(User $user, Request $request, PaginatorInterface $paginator): Response
    {
        $posts = $user->getPost();

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
     * Articles triés par catégorie sélectionnée
     */
    #[Route('/category/list/{id}', name: 'category_posts')]
    public function postsByCategory(Category $category, PostRepository $repo, Request $request, PaginatorInterface $paginator): Response
    {
        $posts = $repo->findByCategory($category);

        // Pagination faite avec le bundle KnpLabs/KnpPaginator
        $orderedPosts = $paginator->paginate(
            $posts,
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('post/list.html.twig',[
            'title' => 'Accueil',
            'posts' => $orderedPosts
        ]);
    }
}
