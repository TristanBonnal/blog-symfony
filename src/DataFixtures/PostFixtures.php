<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        //Création catégories
        $categories = [
            'Divers',
            'Tech',
            'Actu',
            'Web',
        ];
        $categoriesObjects = [];
        foreach ($categories as $category) {
            $newCategory = new Category;
            $newCategory->setTitle($category)
                        ->setDescription($faker->paragraph());
            $categoriesObjects[] = $newCategory;                        
            $manager->persist($newCategory);
        }

        //Création auteurs
        $authorsObjects = [];
        for ($i = 1; $i <= 10; $i++) {
            $newAuthor = new Author;
            $newAuthor->setFirstname($faker->firstName())
                      ->setCreatedAt(new \DateTimeImmutable())
                      ->setLastname($faker->lastName());
            $authorsObjects[] = $newAuthor;
            $manager->persist($newAuthor);         
        }

        //Création posts
        for ($i = 1; $i <= 20; $i++) {
            $post = new Post;
            $post->setTitle(ucfirst($faker->sentence(mt_rand(3,6))))
                 ->setAuthor($authorsObjects[mt_rand(0, count($authorsObjects) - 1)])
                 ->setContent($faker->paragraph(mt_rand(10,30)))
                 ->setImage('https://picsum.photos/id/' . mt_rand(1, 100) . '/550/250')
                 ->setCategory($categoriesObjects[mt_rand(0,count($categoriesObjects) - 1)])
                 ->setCreatedAt(new \DateTimeImmutable());
                 
                 //Création commentaires 
                 for ($j = 3; $j <= 8; $j++) {
                     $comment = new Comment;
                     $comment->setUsername($faker->firstName())
                             ->setContent($faker->paragraph(mt_rand(1,3)))
                             ->setCreatedAt(new \DateTimeImmutable())
                             ->setPost($post);
                     $manager->persist($comment);
                }
                    
            $manager->persist($post);
        }
        $manager->flush();


    }
}
