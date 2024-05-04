<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/blog')]
class BlogController extends AbstractController
{
    const BLOG_POSTS = [
        [
            'id' => 1,
            'title' => 'First Post',
            'slug' => 'first-post',
            'content' => 'This is the first post.'
        ],
        [
            'id' => 2,
            'title' => 'Second Post',
            'slug' => 'second-post',
            'content' => 'This is the second post.'
        ],
        [
            'id' => 3,
            'title' => 'Third Post',
            'slug' => 'third-post',
            'content' => 'This is the third post.'
        ],
    ];

    #[Route('/list/{page}', name: 'blog_list', defaults: ['page' => 5], methods: ['GET'])]
    public function index($page, Request $request): Response
    {
        $limit = $request->get('limit', 10);
        return $this-> json([
            'page' => $page,
            'limit' => $limit,
            'data' => self::BLOG_POSTS
        ]);
    }
    #[Route('/{id}', name: 'blog_by_id', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function getById($id): Response
    {
        return $this-> json(
            self::BLOG_POSTS[array_search($id, array_column(self::BLOG_POSTS, 'id'))]
        );
    }

    #[Route('/{slug}', name: 'blog_by_slug', methods: ['GET'])]
    public function getBySlug($slug): Response
    {
        return $this-> json(
            self::BLOG_POSTS[array_search($slug, array_column(self::BLOG_POSTS, 'slug'))]
        );
    }

    #[Route('/slug/slug-url', name: 'blog_slug_url', methods: ['GET'])]
    public function getSlugUrl(): Response
    {
        return $this-> json(
            [
                'slug-url' => array_map(
                fn($item) => $this->generateUrl('blog_by_slug', ['slug' => $item['slug']]),
                self::BLOG_POSTS)
            ]
        );
    }
}