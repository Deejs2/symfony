<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $this->loadUser($manager);
        $this->loadBlogPost($manager);
    }

    public function loadBlogPost(ObjectManager $manager) : void
    {
        $user = $this->getReference('user_admin');
        $blogPost = new BlogPost();
        $blogPost->setTitle('Why Asteroids Taste Like Bacon')
            ->setPublished(new \DateTime('2021-01-01 12:00:00'))
            ->setContent('Asteroids are the bacon of the sky.')
            ->setAuthor($user)
            ->setSlug('why-asteroids-taste-like-bacon');
        $manager->persist($blogPost);

        $blogPost = new BlogPost();
        $blogPost->setTitle('Life on Mars, how to get there')
            ->setPublished(new \DateTime('2021-01-01 12:00:00'))
            ->setContent('Elon Musk is planning a colony on Mars.')
            ->setAuthor($user)
            ->setSlug('life-on-mars');
        $manager->persist($blogPost);

        $blogPost = new BlogPost();
        $blogPost->setTitle('Jiri the switzerland of nepal')
            ->setPublished(new \DateTime('2021-01-01 12:00:00'))
            ->setContent('Jiri is very beautiful place in nepal.')
            ->setAuthor($user)
            ->setSlug('jiri-the-switzerland-of-nepal');
        $manager->persist($blogPost);

        $manager->flush();
    }

    public function loadUser(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('admin')
            ->setPassword($this->passwordHasher->hashPassword($user, 'admin'))
            ->setName('Admin')
            ->setEmail('admin@example.com');
        $this->addReference('user_admin', $user);
        $manager->persist($user);
        $manager->flush();
    }
}
