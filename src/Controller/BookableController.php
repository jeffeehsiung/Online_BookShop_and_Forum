<?php

namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use function Symfony\Component\String\u;

class BookableController extends AbstractController
{
    private array $stylesheets;

    public function __construct() {
        $this->stylesheets[] = 'home.css';
    }
    #[Route('/settings')]
    public function settings(): Response
    {
        // TODO: split twig templates into file format 'controllername/methodname.html.twig' -> example: 'bookable/settings.html.twig'
        return $this->render('setting.html.twig',[
            'username' => 'test_user',
            'stylesheets' => 'settings.css'
        ]);
    }

    #[Route('/book/{bookTitle}')]
    public function book($bookTitle = null): Response
    {
        if($bookTitle) {
            $bookTitle = u(str_replace('-', ' ', $bookTitle))->title(true);
            return new Response("Book title: $bookTitle");
        } else {
            return new Response('No book title passed');
        }
    }

    #[Route("/Bookable/welcome", name: "welcome")]
    public function Welcome(): Response {
        return $this->render('welcome.html.twig',['title'=>'Welcome!']);
    }

}