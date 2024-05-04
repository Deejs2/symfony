<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $blogPost = new BlogPost();
        $blogPost->setTitle('Why Asteroids Taste Like Bacon')
            ->setPublished(new \DateTime('2021-01-01 12:00:00'))
            ->setContent('Asteroids are the bacon of the sky.')
            ->setAuthor('John')
            ->setSlug('why-asteroids-taste-like-bacon');
        $manager->persist($blogPost);

        $blogPost = new BlogPost();
        $blogPost->setTitle('Life on Mars, how to get there')
            ->setPublished(new \DateTime('2021-01-01 12:00:00'))
            ->setContent('Elon Musk is planning a colony on Mars.')
            ->setAuthor('Chris')
            ->setSlug('life-on-mars');
        $manager->persist($blogPost);

        $blogPost = new BlogPost();
        $blogPost->setTitle('Jiri the switzerland of nepal')
            ->setPublished(new \DateTime('2021-01-01 12:00:00'))
            ->setContent('Jiri is very beautiful place in nepal.')
            ->setAuthor('Dhiraj')
            ->setSlug('jiri-the-switzerland-of-nepal');
        $manager->persist($blogPost);

        $manager->flush();
    }
}
