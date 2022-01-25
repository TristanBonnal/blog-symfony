<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $post = $manager->find(Post::class, rand(84,89));
            $comment = new Comment();
            $comment->setUsername("Anon$i")
                 ->setContent("Contenu du com n° $i")
                 ->setCreatedAt(new \DateTimeImmutable())
                 ->setPost($post);
            $manager->persist($comment);
        }
        $manager->flush();
    }
}
