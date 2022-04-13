<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $repo = $doctrine->getRepository(Article::class);
        $articles = $repo->findAll();
        // dd($articles);
        return $this->render("home/index.html.twig", ["articles" => $articles]);
    }

    /**
     * @Route("/show/{id}", name="show")
     */
    public function show(ManagerRegistry $doctrine, $id): Response
    {
        $repo = $doctrine->getRepository(Article::class);
        $article = $repo->find($id);
        
        if(!$article){
            return $this->redirectToRoute("home");
        }
        
        return $this->render("show/index.html.twig", ["article" => $article]);
    }
}
