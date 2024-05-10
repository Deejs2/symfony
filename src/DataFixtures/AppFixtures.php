<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Entity\User;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    private \Faker\Generator $faker;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        $this-> faker = \Faker\Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $this->loadUser($manager);
        $this->loadBlogPost($manager);
        $this->loadComment($manager);
    }

    public function loadBlogPost(ObjectManager $manager) : void
    {
        $user = $this->getReference('user_admin');
        $slugify = new Slugify();

        for ($i = 0; $i < 10; $i++) {
            $title = $this->faker->realText(30);
            $slug = $slugify->slugify($title);

            $blogPost = new BlogPost();
            $blogPost->setTitle($this->faker->realText(30))
                ->setPublished($this->faker->dateTimeBetween('-1 year', 'now'))
                ->setContent($this->faker->realText())
                ->setAuthor($user)
                ->setSlug($slug);
            $this->addReference("blog_post_$i", $blogPost);
            $manager->persist($blogPost);
        }

        $manager->flush();
    }

    public function loadComment(ObjectManager $manager) : void
    {
        for ($i = 0; $i < 10; $i++) {
            for ($j = 0; $j < mt_rand(1, 10); $j++) {
                $comment = new Comment();
                $comment->setContent($this->faker->realText())
                    ->setPublished($this->faker->dateTimeBetween('-1 year', 'now'))
                    ->setAuthor($this->getReference('user_admin'))
                    ->setBlogPost($this->getReference("blog_post_$i"));
                $manager->persist($comment);
            }
        }

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
