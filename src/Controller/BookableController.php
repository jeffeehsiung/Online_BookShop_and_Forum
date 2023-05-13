<?php

namespace App\Controller;
use App\Entity\Book;
use App\Repository\AvatarRepository;
use App\Repository\BookRepository;
use App\Repository\GenreRepository;
use App\Repository\LibraryRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Safe\Exceptions\PcreException;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/book/{book_id}', name:"book")]
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

    #[Route('/book/{book_id}/vote', name: "book_vote", methods: ['POST'])]
    public function vote(BookRepository $bookRepository, Request $request, EntityManagerInterface $entityManager, $book_id = null) : Response
    {
        // TODO: set and check liked books to enable/disable like system
        $book = $bookRepository->findOneBy(['id' => $book_id]);
        $direction = $request->request->get('direction', 'up');
        if($direction === 'up') {
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