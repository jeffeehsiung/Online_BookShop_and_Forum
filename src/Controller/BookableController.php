<?php

namespace App\Controller;
use App\Repository\AuthorRepository;
use App\Repository\AvatarRepository;
use App\Repository\BookRepository;
use App\Repository\FollowedBookRepository;
use App\Repository\GenreRepository;
use App\Repository\LibraryRepository;
use App\Repository\LikedGenreRepository;
use App\Repository\UserRepository;
use Safe\Exceptions\PcreException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use function Symfony\Component\String\u;

class BookableController extends AbstractController
{
    #[Route('/settings')]
    public function settings(GenreRepository $genreRepository, UserRepository $userRepository): Response
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

    #[Route('/book/{book_id}')]
    public function book(BookRepository $bookRepository, $book_id = null): Response
    {
        $stylesheets = ['book.css'];
        $javascripts = ['book.js'];
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
                'javascripts' => $javascripts,
                'book' => $book
            ]);
        } else {
            return new Response('Error: no book title detected');
        }
    }

    #[Route("/welcome", name: "welcome")]
    public function Welcome(): Response
    {
        $stylesheets = ['welcome.css'];
        $javascripts = ['welcome.js'];
        return $this->render('welcome.html.twig',[
            'title'=>'Welcome!',
            'javascripts' => $javascripts,
            'stylesheets' => $stylesheets
        ]);
    }

    #[Route("/home/{userID}", name: "home")]
    public function Home(AuthorRepository $authorRepository, LikedGenreRepository $likedGenreRepository,
                         UserRepository $userRepository, GenreRepository$genreRepository, BookRepository $bookRepository,
                         FollowedBookRepository $followedBookRepository, $userID = null): Response
    {
        $stylesheets = ['homev2.css'];
        $javascripts = ['home.js'];
        if ($userID) {
            $user = $userRepository->findOneBy(['id' => $userID]);
            $genre_id = $likedGenreRepository->findBy(['user'=>$userID]);
            $genres = $genreRepository->findBy(['id'=>$genre_id, ]);
            $genre_books =  $bookRepository->findBy(['genre'=>$genre_id] );
            $followed = $followedBookRepository->findBy(['user'=>$userID]);
            shuffle($genre_books);
            $authors = $authorRepository->findAll();
            return $this->render('home.html.twig', [
                'title' => 'Home!',
                'stylesheets' => $stylesheets,
                'javascripts' => $javascripts,
                'user' => $user,
                'genres' => $genres,
                'genre_books' => $genre_books,
                'authors' => $authors
            ]);
        }
        else{
            return new Response('Error: no matches detected');
        }
    }


    #[Route("/profile/{userID}")]
    public function Profile(AvatarRepository $avatarRepository, UserRepository $userRepository, $userID = null): Response {
        $stylesheets = ['profile.css'];

        if($userID) {
            $user = $userRepository->findOneBy(['id' => $userID]);
            $avatar = $avatarRepository->find(['id'=> $user->getAvatar()]);
            return $this->render('profile.html.twig', [
                'user' => $user,
                'avatar' => $avatar,
                'stylesheets' => $stylesheets,

            ]);
        } else {
            return new Response('Error: no book title detected');
        }

    }

    #[Route("/browsing", name: "browsing")]
    public function browsing(): Response {
        $stylesheets = ['browsing.css'];
        return $this->render('browsing.html.twig',[
            'title'=>'Browser',
            'stylesheets' => $stylesheets]);
    }

}