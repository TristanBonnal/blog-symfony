<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $post = new Post;
            $author = new Author;
            $post->setTitle("Article n° $i")
                 ->setAuthor($author)
                 ->setContent("Contenu de l'article n° $i")
                 ->setImage("https://place-hold.it/" . random_int(400, 600) . "x" . random_int(200, 400))
                 ->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($post);
        }
        $manager->flush();
    }
}
