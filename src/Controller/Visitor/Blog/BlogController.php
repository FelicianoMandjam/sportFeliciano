<?php
namespace App\Controller\Visitor\Blog;

use App\Entity\Tag;
use App\Entity\Post;
use App\Entity\Category;
use App\Repository\TagRepository;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'visitor.blog.index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository, TagRepository $tagRepository,PostRepository $postRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $tags = $tagRepository->findAll();
        $posts = $postRepository->findBy(["isPublished" => true]);
        
        return $this->render('pages/visitor/blog/index.html.twig', [
            "categories" => $categories,
            "tags"       => $tags,
            "posts"       => $posts,
        ]);
    }

    #[Route('/blog/posts/filter-by-category/{id}/{slug}', name: 'visitor.blog.posts.filter_by_category', methods:['GET'])]
    public function postsFilterByCategory(
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository,
        PostRepository $postRepository,
        Category $category
    ): Response
    {

        $categories = $categoryRepository->findAll();
        $tags       = $tagRepository->findAll();
        $posts      = $postRepository->findPostsByCategory($category->getId());

        return $this->render('pages/visitor/blog/index.html.twig', [
            "categories" => $categories,
            "tags"       => $tags,
            "posts"      => $posts
        ]);
    }

    #[Route('/blog/posts/filter-by-tag/{id}/{slug}', name: 'visitor.blog.posts.filter_by_tag', methods:['GET'])]
    public function postsFilterByTag(
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository,
        PostRepository $postRepository,
        Tag $tag
    ): Response
    {

        $categories = $categoryRepository->findAll();
        $tags       = $tagRepository->findAll();
        $posts      = $postRepository->findPostsByTag($tag->getId());

        return $this->render('pages/visitor/blog/index.html.twig', [
            "categories" => $categories,
            "tags"       => $tags,
            "posts"      => $posts
        ]);
    }
    
    #[Route('/blog/posts/{id}/{slug}/show', name: 'visitor.blog.posts.show', methods:['GET'])]
    public function show(Post $post) : Response 
    {
        $this->render('pages/visitor/blog/show.html.twig', [
            "post" => $post
        ] );
    }


}
