<?php
namespace App\Controller\Visitor\Blog;

use App\Entity\Tag;
use App\Entity\Post;
use App\Entity\Comment;
use App\Entity\Category;
use App\Form\CommentFormType;
use App\Repository\TagRepository;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
class BlogController extends AbstractController
{
    #[Route('/admin/comment/{id}/activate', name: 'admin.comment.activate', methods:['PUT'])]
    public function publish(Comment $comment, Request $request, EntityManagerInterface $em): Response
    {
        // Si le CSRF TOKEN est valide
        if ( $this->isCsrfTokenValid('activate_comment_'.$comment->getId(), $request->request->get('csrf_token')) ) 
        {
            // Si l'commentaire n'est pas encore publié
            if ( false === $comment->isIsActivated() ) 
            {
                // Publier l'commentaire
                $comment->setIsActivated(true);   

                // Générer le message flash correspondant
                $this->addFlash('success', "Le commentaire a été activé.");
            }
            else // Dans le cas contraire,
            {
                // Retirer l'commentaire de la liste des publications
                $comment->setIsActivated(false);

                // Générer le message flash correspondant
                $this->addFlash('success', "Le commentaire a été désactivé.");
            }
                
            // Demander au manager des entités de préparer la requête de modification de cet article
            $em->persist($comment);

            // Demander au manager d'exécuter la requête
            $em->flush();
        }

        return $this->redirectToRoute('admin.comment.index');
    }

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
    


     #[Route('/blog/post/{id}/{slug}/show', name: 'visitor.blog.post.show', methods:['GET', 'POST'])]
    public function show( Post $post,Request $request, EntityManagerInterface $em, CommentRepository $commentRepository ) : Response 
    {
       $comments = $commentRepository->findBy(['isActivated' => true]) ;

        $comment = new Comment();

        $form = $this->createForm(CommentFormType::class, $comment);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) 
        {

            $comment->setPost($post);
            $comment->setUser($this->getUser());

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('visitor.blog.post.show', [
                "id" => $post->getId(),
                "slug" => $post->getSlug(),
                "comments" => $comments
            ]);
        }

        return $this->render('pages/visitor/blog/show.html.twig', [
            "post" => $post,
            "form" => $form->createView(),
            "comments" => $comments
            
        ]);
       

        }
}
