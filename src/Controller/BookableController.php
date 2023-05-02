<?php

namespace App\Controller;
use App\Repository\BookRepository;
use App\Repository\GenreRepository;
use Safe\Exceptions\PcreException;
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
    public function book(BookRepository $bookRepository, $book_id = null): Response
    {
        $stylesheets = ['book.css'];
        if($book_id) {
            $book = $bookRepository->findOneBy(['id' => $book_id]);
            try {
                $bookTitle = u(preg_replace("/\([^)]+\)/", "", $book->getTitle()))->title(true);
            } catch (PcreException $e) {
                $bookTitle = $e;
            }
            return $this->render('book.html.twig', [
                'bookTitle' => $bookTitle,
                'stylesheets' => $stylesheets,
                'book' => $book
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

    #[Route("/profile", name: "profile")]
    public function Profile(): Response {
        $stylesheets = ['profile.css'];
        return $this->render('profile.html.twig',[
            'title'=>'Profile',
            'stylesheets' => $stylesheets]);
    }

    #[Route("/browsing", name: "browsing")]
    public function browsing(GenreRepository $genreRepository, BookRepository $bookRepository): Response {
        // genreRepository is used to get all genres from the database
        $bookGenres = $genreRepository->findAll();
        // declare bookRepository to be used to get all books from the database table books
        $books = $bookRepository->findAll();
        $stylesheets = ['browsing.css'];
        $javascripts = ['browsing.js'];
        return $this->render('browsing.html.twig',[
            'title'=>'Browser',
            'stylesheets' => $stylesheets,
            'bookgenres' => $bookGenres,
            'books' => $books,
            'javascripts' => $javascripts

        ]);
    }

}