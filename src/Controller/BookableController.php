<?php

namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use function Symfony\Component\String\u;

class BookableController extends AbstractController
{
    #[Route('/settings')]
    public function settings(): Response
    {
        // TODO: split twig templates into file format 'controllername/methodname.html.twig' -> example: 'bookable/settings.html.twig'
        $stylesheets = ['settings.css'];
        $javascripts = ['settings.js'];
        $bookGenres = array(
            "Fiction",
            "Mystery",
            "Romance",
            "Science Fiction",
            "Fantasy",
            "Thriller",
            "Biography",
            "History",
            "Self-help",
            "Horror",
            "Cooking",
            "Travel",
            "Art",
            "Business",
            "Religion",
            "Humor",
            "Children's",
            "Young Adult"
        );

        return $this->render('setting.html.twig',[
            'username' => 'test_user',
            'stylesheets' => $stylesheets,
            'javascripts' => $javascripts,
            'bookgenres' => $bookGenres
        ]);
    }

    #[Route('/book/{bookTitle}')]
    public function book($bookTitle = null): Response
    {
        $stylesheets = ['book.css'];
        $javascripts = ['book.js'];
        if($bookTitle) {
            $bookTitle = u(str_replace('-', ' ', $bookTitle))->title(true);
            return $this->render('book.html.twig', [
                'bookTitle' => $bookTitle,
                'stylesheets' => $stylesheets,
                'javascripts' => $javascripts
            ]);
        } else {
            return new Response('Error: no book title detected');
        }
    }

    #[Route("/welcome", name: "welcome")]
    public function Welcome(): Response {
        $stylesheets = ['welcome.css'];
        return $this->render('welcome.html.twig',[
            'title'=>'Welcome!',
            'stylesheets' => $stylesheets]);
    }
}