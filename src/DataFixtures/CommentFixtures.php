<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Repository\PostRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $post = $repo->find(2);
        for ($i = 1; $i <= 5; $i++) {
            $comment = new Comment();
            $comment
                ->setContent("Contenu du commentaire n° $i")
                ->setUsername('Anonymous')
                ->setPost($post)
                ->setCreatedAt(new \DateTimeImmutable());
                $manager->persist($comment);
        }


        $manager->flush();
    }
}
