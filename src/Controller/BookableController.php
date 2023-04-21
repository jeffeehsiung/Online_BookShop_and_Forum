<?php

namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class BookableController extends AbstractController
{
    #[Route('/')]
    public function homepage()
    {
        die('Bookable');
    }
    #[Route("/Bookable/welcome", name: "welcome")]
    public function Welcome(): Response {
        return $this->render('welcome.html.twig',['title'=>'Welcome!']);
    }

}