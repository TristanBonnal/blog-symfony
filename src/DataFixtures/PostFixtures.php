<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PostFixtures extends Fixture
{
    public function __construct (UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        //Création catégories
        $categories = [
            'Divers',
            'Tech',
            'Actu',
            'Web',
            'Geek',
            'Science'
        ];
        $categoriesObjects = [];
        foreach ($categories as $category) {
            $newCategory = new Category;
            $newCategory->setTitle($category)
                        ->setDescription($faker->paragraph());
            $categoriesObjects[] = $newCategory;                        
            $manager->persist($newCategory);
        }

        // Création utilisateurs
        // Admin
        $usersObjects = [];
        $newAdmin = new User;
        $newAdmin
            ->setFirstname($faker->firstName())
            ->setLastname($faker->lastName())
            ->setRoles(['ROLE_ADMIN'])
            ->setEmail('admin@admin.com')
            ->setPassword($this->hasher->hashPassword($newAdmin, 'admin'))
            ->setCreatedAt($faker->dateTimeBetween('-2 months', '-1 month'))
        ;
        $manager->persist($newAdmin);
        $usersObjects[] = $newAdmin;

        // Modérateur
        $newModerator = new User;
        $newModerator
            ->setFirstname($faker->firstName())
            ->setLastname($faker->lastName())
            ->setRoles(['ROLE_MODERATOR'])
            ->setEmail('moderator@moderator.com')
            ->setPassword($this->hasher->hashPassword($newModerator, 'admin'))
            ->setCreatedAt($faker->dateTimeBetween('-2 months', '-1 month'))
        ;
        $manager->persist($newModerator);
        $usersObjects[] = $newModerator;
        for ($i = 1; $i <= 10; $i++) {
            $newUser = new User;
            $newUser
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setRoles(['ROLE_MODERATOR'])
                ->setEmail($faker->email())
                ->setPassword($this->hasher->hashPassword($newUser, 'admin'))
                ->setCreatedAt($faker->dateTimeBetween('-2 months', '-1 month'))
                ;
            $usersObjects[] = $newUser;
            $manager->persist($newUser);         
        }

        //Création posts
        for ($i = 1; $i <= 20; $i++) {
            $post = new Post;
            $post->setTitle(ucfirst($faker->sentence(mt_rand(3,6))))
                 ->setUser($usersObjects[mt_rand(0, count($usersObjects) - 1)])
                 ->setContent($faker->paragraph(mt_rand(10,30)))
                 ->setImage('https://picsum.photos/id/' . mt_rand(1, 100) . '/550/250')
                 ->setCategory($categoriesObjects[mt_rand(0,count($categoriesObjects) - 1)])
                 ->setCreatedAt($faker->dateTimeBetween('-1 week', '+1 week'));
            $manager->persist($post);
                 
                 //Création commentaires 
                 for ($j = 3; $j <= 8; $j++) {
                    $daysDiff = (new \DateTime())->diff($post->getCreatedAt())->days;

                     $comment = new Comment;
                     $comment->setUser($usersObjects[array_rand($usersObjects)])
                             ->setContent($faker->paragraph(mt_rand(1,3)))
                             ->setCreatedAt(($faker->dateTimeBetween('-' . $daysDiff .'days')))
                             ->setPost($post);
                     $manager->persist($comment);
                }     
        }
        $manager->flush();
    }
}
