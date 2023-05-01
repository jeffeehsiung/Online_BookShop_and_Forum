<?php

namespace App\Controller;
use App\Repository\BookRepository;
use App\Repository\GenreRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use function Symfony\Component\String\u;

class BookableController extends AbstractController
{
    #[Route('/settings')]
    public function settings(GenreRepository $genreRepository): Response
    {
        $bookGenres = $genreRepository->findAll();
        // TODO: split twig templates into file format 'controllername/methodname.html.twig' -> example: 'bookable/settings.html.twig'
        $stylesheets = ['settings.css'];
        $javascripts = ['settings.js'];
        return $this->render('setting.html.twig',[
            'username' => 'test_user',
            'stylesheets' => $stylesheets,
            'javascripts' => $javascripts,
            'bookgenres' => $bookGenres
        ]);
    }

    #[Route('/book/book_id={book_id}')]
    public function book($book_id = null, BookRepository $bookRepository): Response
    {
        $stylesheets = ['book.css'];
        $javascripts = ['book.js'];
        if($book_id) {
            $bookTitle = $bookRepository->findOneBy(['id' => $book_id])->getTitle();
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

    #[Route("/home", name: "home")]
    public function Home(): Response {
        $stylesheets = ['homev2.css'];
        return $this->render('home.html.twig',[
            'title'=>'Home!',
            'stylesheets' => $stylesheets]);
    }
}