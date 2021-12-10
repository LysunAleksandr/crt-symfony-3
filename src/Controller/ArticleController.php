<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\StaticPage;
use App\Form\CommentFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\StaticPageRepository;

class ArticleController extends AbstractController
{
    private $twig;
    private $entityManager;


    public function __construct(Environment $twig, EntityManagerInterface $entityManager)
    {
        $this->twig = $twig;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'homepage')]
    public function index(Request $request,ArticleRepository $articleRepository ): Response
    {
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $articleRepository->getArticlePaginator($offset);

        return new Response($this->twig->render('article/index.html.twig', [
            //  'articles' => $articleRepository->findAll(),
           // 'static_pages' =>$staticPageRepository->findAll(),
            'articles' => $paginator,
            'previous' => $offset - ArticleRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + ArticleRepository::PAGINATOR_PER_PAGE),
        ]));
    }

     #[Route('/article/{id}', name: 'article')]
    public function show(Request $request, Article $article, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setArticle($article);
            //var_dump(isNull($comment->getCreatedAt()));
           // $logger->info(isNull($comment->getCreatedAt()));
            $comment->setCreatedAtValue();
            $comment->setUpdatedAt( null);
            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return $this->redirectToRoute('article',  ['id' => $article->getId()]);
        }

        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentRepository->getCommentPaginator($article, $offset);

        return new Response($this->twig->render('article/show.html.twig', [
            'article' => $article,
           // 'autor' => $article->getAutor(),
           // 'content' => $article->getContent(),
            'created_at' => $article->getCreatedAt()->format(('Y-m-d H:i:s')),
           // 'title_str' => $article->getTitle(),
            'comments' => $paginator,
            'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE),
            'comment_form' => $form->createView(),
        ]));
    }


    #[Route('/about', name: 'about')]
    public function about(ArticleRepository $articleRepository): Response
    {

        return new Response($this->twig->render('about.html.twig'));

    }

    #[Route('/static/{id}', name: 'static')]
    public function showstatic(Request $request, StaticPage $staticPage, StaticPageRepository $staticPageRepository): Response
    {

        return new Response($this->twig->render('static.html.twig', [
            'static_text' => $staticPage->getText(),
            'static_title' => $staticPage->getTitle(),
           // 'static_pages'=> $staticPageRepository->findAll(),

        ]));
    }

}
