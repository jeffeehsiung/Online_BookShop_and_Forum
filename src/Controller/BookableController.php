<?php

namespace App\Controller;
use App\Repository\AvatarRepository;
use App\Repository\BookRepository;
use App\Repository\FollowedBookRepository;
use App\Repository\GenreRepository;
use App\Repository\LibraryRepository;
use App\Repository\LikedGenreRepository;
use App\Repository\UserRepository;
use Safe\Exceptions\PcreException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use function Symfony\Component\String\u;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class BookableController extends AbstractController
{
    #[Route('/settings')]
    public function settings(GenreRepository $genreRepository, UserRepository $userRepository): Response
    {
        $bookGenres = $genreRepository->findAll();
        // TODO: split twig templates into file format 'controllername/methodname.html.twig' -> example: 'bookable/settings.html.twig'
        $stylesheets = ['settings.css'];
        $javascripts = ['settings.js'];

        return $this->render('setting.html.twig', [
            'username' => 'test_user',
            'stylesheets' => $stylesheets,
            'javascripts' => $javascripts,
            'bookgenres' => $bookGenres
        ]);
    }

    #[Route('/book/{book_id}', name: "book")]
    public function book(BookRepository $bookRepository, $book_id = null): Response
    {
        $stylesheets = ['book.css'];
        $javascripts = ['book.js'];
        if ($book_id) {
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

    #[Route('/book/{book_id}/vote', name: "book_vote", methods: ['POST'])]
    public function vote(BookRepository $bookRepository, Request $request, EntityManagerInterface $entityManager, $book_id = null): Response
    {
        // TODO: set and check liked books to enable/disable like system
        $book = $bookRepository->findOneBy(['id' => $book_id]);
        $direction = $request->request->get('direction', 'up');
        if ($direction === 'up') {
            $book->setLikes($book->getLikes() + 1);
        } else {
            $book->setLikes($book->getLikes() - 1);
        }

        $entityManager->flush();
        return $this->redirectToRoute("book", [
            'book_id' => $book_id
        ]);
    }

    #[Route("/welcome", name: "welcome")]
    public function Welcome(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $stylesheets = ['welcome.css'];
        $javascripts = ['welcome.js'];
        return $this->render('welcome.html.twig', [
            'title' => 'Welcome!',
            'controller_name' => 'BookableController',
            'last_username' => $lastUsername,
            'error' => $error,
            'javascripts' => $javascripts,
            'stylesheets' => $stylesheets
        ]);
    }

    #[Route("/home/{userID}", name: "home")]
    public function Home(LikedGenreRepository   $likedGenreRepository,
                         UserRepository         $userRepository, GenreRepository $genreRepository, BookRepository $bookRepository,
                         FollowedBookRepository $followedBookRepository, $userID = null): Response
    {
        $stylesheets = ['homev2.css'];
        $javascripts = ['home.js'];
        if ($userID) {
            $user = $userRepository->findOneBy(['id' => $userID]);
            $books = $bookRepository->findAll();
            $genre_id = $likedGenreRepository->findBy(['user' => $userID]);
            $genres = $genreRepository->findBy(['id' => $genre_id,]);
            $genre_books = $bookRepository->findBy(['genre' => $genre_id]);
            $followed = $followedBookRepository->findBy(['user' => $userID]);
            $followed_authors = [];
            $followed_books = $bookRepository->findBy(['id' => $followed]);
            foreach ($followed_books as $followed_book) {
                $current_author = $followed_book->getAuthor();
                $followed_authors[] = $current_author;
            }
            $popular_books = $bookRepository->findPopular();


            shuffle($books);
            shuffle($genre_books);
            return $this->render('home.html.twig', [
                'title' => 'Home!',
                'stylesheets' => $stylesheets,
                'javascripts' => $javascripts,
                'user' => $user,
                'books' => $books,
                'genres' => $genres,
                'genre_books' => $genre_books,
                'followed_authors' => $followed_authors,
                'popular_books' => $popular_books
            ]);
        } else {
            return new Response('Error: no matches detected');
        }
    }

    #[Route("/profile/{userID}")]
    public function Profile(AvatarRepository $avatarRepository, UserRepository $userRepository, $userID = null): Response
    {
        $stylesheets = ['profile.css'];

        if ($userID) {
            $user = $userRepository->findOneBy(['id' => $userID]);
            $avatar = $avatarRepository->find(['id' => $user->getAvatar()]);
            return $this->render('profile.html.twig', [
                'user' => $user,
                'avatar' => $avatar,
                'stylesheets' => $stylesheets,

            ]);
        } else {
            return new Response('Error: no book title detected');
        }

    }
    #[Route("/about", name:"about")]
    public function About(): Response
    {
        $stylesheets = ['about.css'];
        $javascripts = ['about.js'];
        return$this->render('about.html.twig', [
            'stylesheets'=> $stylesheets,
            'javascripts'=>$javascripts
        ]);
        }



    #[Route("/browsing", name: "browsing")]
    public function browsing(GenreRepository $genreRepository, BookRepository $bookRepository): Response
    {
        // genreRepository is used to get all genres from the database
        $bookGenres = $genreRepository->findAll();
        // declare a booksperpage variable to be used to get 20 books from the database table books
        $booksPerPage = 20;
        // declare bookRepository to be used to get 20 books from the database table books
        $books = $bookRepository->findBy([], [], $booksPerPage);
        $allBooks = $bookRepository->findAll();
        // parse all books into json format variable
        $booksjson = json_encode($allBooks);
        // declare stylesheets and javascripts to be used in the twig template
        $stylesheets = ['browsing.css'];
        $javascripts = ['browsing.js'];
        return $this->render('browsing.html.twig', [
            'title' => 'Browser',
            'stylesheets' => $stylesheets,
            'genres' => $bookGenres,
            'books' => $books,
            'booksperpage' => $booksPerPage,
            'booksjson' => $booksjson,
            'javascripts' => $javascripts

        ]);
    }




}