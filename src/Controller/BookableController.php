<?php

namespace App\Controller;
use App\Entity\DislikedBook;
use App\Entity\LikedBook;
use App\Entity\User;
use App\Entity\Book;
use App\Repository\AvatarRepository;
use App\Repository\BookRepository;
use App\Repository\DislikedBookRepository;
use App\Repository\FollowedBookRepository;
use App\Repository\GenreRepository;
use App\Repository\LibraryRepository;
use App\Repository\LikedBookRepository;
use App\Repository\LikedGenreRepository;
use App\Repository\ReadBooksRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Safe\Exceptions\PcreException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use function Symfony\Component\String\u;
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

        return $this->render('setting.html.twig',[
            'username' => 'test_user',
            'stylesheets' => $stylesheets,
            'javascripts' => $javascripts,
            'bookgenres' => $bookGenres
        ]);
    }

    #[Route('/book/{book_id}', name:"book")]
    public function book(BookRepository $bookRepository, LikedBookRepository $likedBookRepository, DislikedBookRepository $dislikedBookRepository, $book_id = null): Response
    {
        // Fetch user
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        // Render page
        $stylesheets = ['book.css'];
        $javascripts = ['book.js'];
        if($book_id) {
            $book = $bookRepository->findOneBy(['id' => $book_id]);

            // Check if book is liked or disliked
            $isLiked = false;
            $isDisliked = false;
            $likedBook = $likedBookRepository->findOneBy([
                'user' => $user,
                'book' => $book
            ]);
            if($likedBook)
                $isLiked = true;
            else {
                $dislikedBook = $dislikedBookRepository->findOneBy([
                    'user' => $user,
                    'book' => $book
                ]);
                if($dislikedBook)
                    $isDisliked = true;
            }

            // Beautify title
            try {
                $bookTitle = u(preg_replace("/\([^)]+\)/", "", $book->getTitle()))->title(true);
            } catch (PcreException $e) {
                $bookTitle = $e;
            }
            return $this->render('book.html.twig', [
                'bookTitle' => $bookTitle,
                'stylesheets' => $stylesheets,
                'javascripts' => $javascripts,
                'book' => $book,
                'isLiked' => $isLiked,
                'isDisliked' => $isDisliked
            ]);
        } else {
            return new Response('Error: no book title detected');
        }
    }

    #[Route('/book/{book_id}/vote', name: "book_vote", methods: ['POST'])]
    public function like
    (
        BookRepository $bookRepository, LikedBookRepository $likedBookRepository,
        DislikedBookRepository $dislikedBookRepository, Request $request, EntityManagerInterface $entityManager,
        $book_id = null
    ) : Response
    {
        // Fetch user
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $book = $bookRepository->findOneBy(['id' => $book_id]);

        // Update likes
        $direction = $request->request->get('direction', 'like-up');
        if($direction === 'like-up') {
            // Set as liked book in DB
            $likedBook = new LikedBook();
            $likedBook->setUser($user);
            $likedBook->setBook($book);
            $entityManager->persist($likedBook);
            $book->setLikes($book->getLikes() + 1);
        } elseif($direction === 'like-down'){
            // Unlike book
            $likedBook = $likedBookRepository->findOneBy([
                'user' => $user,
                'book' => $book
            ]);
            $entityManager->remove($likedBook);
            $book->setLikes($book->getLikes() - 1);
        } elseif($direction === 'dislike-up') {
            // Set as disliked book in DB
            $dislikedBook = new DislikedBook();
            $dislikedBook->setUser($user);
            $dislikedBook->setBook($book);
            $entityManager->persist($dislikedBook);

            $book->setDislikes($book->getDislikes() + 1);
        } else {
            // Un-dislike book
            $dislikedBook = $dislikedBookRepository->findOneBy([
                'user' => $user,
                'book' => $book
            ]);
            $entityManager->remove($dislikedBook);
            $book->setDislikes($book->getDislikes() - 1);
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
        return $this->render('welcome.html.twig',[
            'title'=>'Welcome!',
            'controller_name' => 'BookableController',
            'last_username' => $lastUsername,
            'error'         => $error,
            'javascripts' => $javascripts,
            'stylesheets' => $stylesheets
        ]);
    }

    #[Route("/home/{userID}", name: "home")]
    public function Home(LikedGenreRepository $likedGenreRepository,
        UserRepository $userRepository, GenreRepository$genreRepository, BookRepository $bookRepository,
        FollowedBookRepository $followedBookRepository, $userID = null): Response
    {
        // Fetch user
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $userID = $this->getUser()->getId();

        $stylesheets = ['homev2.css'];
        $javascripts = ['home.js'];
        if ($userID) {
            $user = $userRepository->findOneBy(['id' => $userID]);
            $books = $bookRepository->findAll();
            $genre_id = $likedGenreRepository->findBy(['user'=>$userID]);
            $genres = $genreRepository->findBy(['id'=>$genre_id, ]);
            $genre_books =  $bookRepository->findBy(['genre'=>$genre_id] );
            $followed = $followedBookRepository->findBy(['user'=>$userID]);
            $followed_authors = [];
            $followed_books = $bookRepository->findBy(['id'=>$followed]);
            foreach ($followed_books as $followed_book){
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
        }
        else{
            return new Response('Error: no matches detected');
        }
    }

    #[Route("/profile/{userID}")]
    public function Profile(AvatarRepository $avatarRepository,ReadBooksRepository $readBookRepository, BookRepository $bookRepository,FollowedBookRepository $followedBookRepository, UserRepository $userRepository, $userID = null): Response {
        $stylesheets = ['profile.css'];

        if($userID) {
            $user = $userRepository->findOneBy(['id' => $userID]);
            $avatar = $avatarRepository->find(['id'=> $user->getAvatar()]);


            $followed_id = $followedBookRepository->findBy(['user'=>$user]);
            $follow_book = $bookRepository->findBy(['id'=>$followed_id]);
            $read_id = $readBookRepository->findBy(['user'=>$user]);
            $read_book = $bookRepository->findBy(['id'=>$read_id]);


            return $this->render('profile.html.twig', [
                'user' => $user,
                'avatar' => $avatar,
                'followed_book'=> $follow_book,
                'read_list'=> $read_book,
                'stylesheets' => $stylesheets,

            ]);
        } else {
            return new Response('Error: no book title detected');
        }

    }

    #[Route("/browsing/{book_title}", name: 'browsing')]
    public function browsing(GenreRepository $genreRepository, BookRepository $bookRepository,
        $book_title = null): Response {
        // genreRepository is used to get all genres from the database
        $bookGenres = $genreRepository->findAll();
        // declare a booksperpage variable to be used to get 20 books from the database table books
        $booksPerPage = 20;
        // if a book title is passed in the url, then get all books with that title
        $booktitle = $book_title? u(str_replace('-',' ',$book_title))->title(true) : null;
        // if a book title is null, then get all books will be returned
        $books = $bookRepository->findAllByTitle($booktitle, $booksPerPage);
        // get the length of the books array
        $booksCount = count($books);
        // declare stylesheets and javascripts to be used in the twig template
        $stylesheets = ['browsing.css'];
        $javascripts = ['browsing.js'];
        return $this->render('browsing.html.twig',[
            'title'=>'Browser',
            'stylesheets' => $stylesheets,
            'genres' => $bookGenres,
            'books' => $books,
            'booksperpage' => $booksPerPage,
            'bookscount' => $booksCount,
            'javascripts' => $javascripts

        ]);
    }



}
