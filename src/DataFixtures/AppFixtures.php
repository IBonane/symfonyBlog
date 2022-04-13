<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Faker\Factory::create('fr_FR');
        $users = [];
        for ($i=0; $i < 50; $i++) { 
            $user = new User();
            $user->setUsername($faker->name);
            $user->setFirstname($faker->firstname());
            $user->setLastname($faker->lastname());
            $user->setEmail($faker->email);
            $user->setPassword($faker->password());
            $user->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($user);
            $users[] = $user;
        }

        $categories = [];
        for ($i=0; $i < 15; $i++) { 
            $category = new Category();
            $category->setTitle($faker->text(50));
            $category->setDescription($faker->text(250));
            $category->setImage("https://picsum.photos/640/480?random=".mt_rand(1, 55000));
            $manager->persist($category);
            $categories[] = $category;
        }

        for ($i=0; $i < 100; $i++) { 
            $article = new Article();
            $article->setTitle($faker->text(50));
            $article->setContent($faker->text(6000));
            $article->setImage("https://picsum.photos/640/480?random=".mt_rand(1, 55000));
            $article->setCreatedAt(new \DateTimeImmutable());
            $article->addCategory($categories[$faker->numberBetween(0,14)]);
            $article->setAuthor($users[$faker->numberBetween(0,49)]);
            $manager->persist($article);
        }

        $manager->flush();
    }
}
