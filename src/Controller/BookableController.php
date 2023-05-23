<?php

namespace App\Controller;
use App\Repository\AvatarRepository;
use App\Repository\BookRepository;
use App\Repository\FollowedBookRepository;
use App\Repository\GenreRepository;
use App\Repository\LibraryRepository;
use App\Repository\ReadBooksRepository;
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

    #[Route("/home", name: "home")]
    public function Home(): Response {
        $stylesheets = ['homev2.css'];
        $javascripts = ['home.js'];
        return $this->render('home.html.twig',[
            'title'=>'Home!',
            'stylesheets' => $stylesheets,
            'javascripts' =>$javascripts]);

    }

    #[Route("/profile/{userID}")]
    public function Profile(AvatarRepository $avatarRepository,ReadBooksRepository $readBookRepository, BookRepository $bookRepository,FollowedBookRepository $followedBookRepository, UserRepository $userRepository, $userID = null): Response {
        $stylesheets = ['profile.css'];

        if($userID) {
            $user = $userRepository->findOneBy(['id' => $userID]);

            $avatar = $user->getAvatar();


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

    #[Route("/browsing", name: "browsing")]
    public function browsing(): Response {
        $stylesheets = ['browsing.css'];
        return $this->render('browsing.html.twig',[
            'title'=>'Browser',
            'stylesheets' => $stylesheets]);
    }

}