<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/v1/blog')]
class BlogController extends AbstractController
{
    #[Route('/list/{page}', name: 'blog_list', defaults: ['page' => 5], methods: ['GET'])]
    public function index($page, Request $request, EntityManagerInterface $em): Response
    {
        $repository = $em->getRepository(BlogPost::class);
        $limit = $request->get('limit', 10);
        return $this-> json([
            'page' => $page,
            'limit' => $limit,
            'data' => $repository->findAll()
        ]);
    }
    #[Route('/{id}', name: 'blog_by_id', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function getById($id, EntityManagerInterface $em): Response
    {
        return $this-> json(
            $em->getRepository(BlogPost::class)->find($id)
            );
    }

    #[Route('/{slug}', name: 'blog_by_slug', methods: ['GET'])]
    public function getBySlug($slug, EntityManagerInterface $em): Response
    {
        return $this-> json(
            $em->getRepository(BlogPost::class)->findOneBy(['slug'=>$slug])
        );
    }

    #[Route('/slug/slug-url', name: 'blog_slug_url', methods: ['GET'])]
    public function getSlugUrl(EntityManagerInterface $em): Response
    {
        $items = $em->getRepository(BlogPost::class)->findAll();
        return $this-> json(
            [
                'slug-url' => array_map(
                fn($item) => $this->generateUrl('blog_by_slug', ['slug' => $item->getSlug()]),
                $items)
            ]
        );
    }

    #[Route('/add', name: 'blog_add', methods: ['POST'])]
    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $em): Response
    {
        $blogPost = $serializer->deserialize($request->getContent(), BlogPost::class, 'json');

        $em->persist($blogPost);
        $em->flush();

        return $this-> json($blogPost);
    }

    #[Route('/{id}', name: 'blog_delete', methods: ['DELETE'])]
    public function delete(BlogPost $post, EntityManagerInterface $em): Response
    {
        $em->remove($post);
        $em->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}