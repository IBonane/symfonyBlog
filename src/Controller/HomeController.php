<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class HomeController extends AbstractController
{
    private $repoArticle, $repoCategory;

    public function __construct(ArticleRepository $repoArticle, CategoryRepository $repoCategory)
    {
        $this->repoArticle = $repoArticle;
        $this->repoCategory = $repoCategory;
    }
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $articles = $this->repoArticle->findAll();
        $categories = $this->repoCategory->findAll();
        return $this->render(
            "home/index.html.twig",
            [
                "articles" => $articles,
                "categories" => $categories
            ]
        );
    }

    /**
     * @Route("/show/{id}", name="show")
     */
    public function show(Article $article): Response
    {
        // $article = $this->repoArticle->find($id);

        if (!$article) {
            return $this->redirectToRoute("home");
        }

        return $this->render(
            "show/index.html.twig",
            [
                "article" => $article
            ]
        );
    }

    /**
     * @Route("/showArticle/{id}", name="show_article")
     */
    public function showArticle(?Category $category): Response
    {
        if (!$category) {
            return $this->redirectToRoute('home');
        }
        $articles = $category->getArticles()->getValues();

        $categories = $this->repoCategory->findAll();

        return $this->render(
            "home/index.html.twig",
            [
                "articles" => $articles,
                "categories" => $categories
            ]
        );
    }
}
