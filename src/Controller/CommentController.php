<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    #[Route('post/{id}/comment/new', name: 'comment_new')]
    public function add(Request $request, EntityManagerInterface $manager, Post $post): Response
    {
        $comment = new Comment;
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTime());
            $post->addComment($comment);
            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('post', ['id' => $post->getId()]);
        }

        return $this->renderForm('comment/form.html.twig', [
            'title' => 'Commentez',
            'commentForm' => $form
        ]);
    }
}
