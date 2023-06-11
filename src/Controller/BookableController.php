<?php

namespace App\Controller;
use App\Entity\DislikedBook;
use App\Entity\FollowedBook;
use App\Entity\LikedBook;
use App\Entity\User;
use App\Form\BookFilterFormType;
use App\Form\BookSearchFormType;
use App\Repository\BookRepository;
use App\Repository\DislikedBookRepository;
use App\Repository\FollowedBookRepository;
use App\Repository\GenreRepository;
use App\Repository\LikedBookRepository;
use App\Repository\LikedGenreRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\AbstractString;
use Symfony\Component\String\UnicodeString;
use function Symfony\Component\String\u;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class BookableController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function base(): Response
    {
        return $this->redirectToRoute('welcome');
    }

    #[Route('/book/{book_id}', name: 'book')]
    public function book
    (
        BookRepository $bookRepository, LikedBookRepository $likedBookRepository,
        FollowedBookRepository $followedBookRepository, DislikedBookRepository $dislikedBookRepository, $book_id = null
    ): Response
    {
        // Fetch user
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        // Render page
        $stylesheets = ['book.css'];
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

            // Check if book if followed
            $isFollowed = !is_null($followedBookRepository->findOneBy([
                'user' => $user,
                'book' => $book
            ]));

            // Beautify title
            $bookTitle = u(preg_replace('/\([^)]+\)/', '', $book->getTitle()))->title(true);
            return $this->render('book.html.twig', [
                'bookTitle' => $bookTitle,
                'stylesheets' => $stylesheets,
                'book' => $book,
                'isLiked' => $isLiked,
                'isDisliked' => $isDisliked,
                'isFollowed' => $isFollowed
            ]);
        } else {
            return new Response('Error: no book title detected');
        }
    }

    #[Route('/book/{book_id}/vote', name: 'book_vote', methods: ['POST'])]
    public function vote
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
        $direction = $request->request->get('direction');
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
            // Set as disliked book
            $dislikedBook = new DislikedBook();
            $dislikedBook->setUser($user);
            $dislikedBook->setBook($book);
            $entityManager->persist($dislikedBook);

            $book->setDislikes($book->getDislikes() + 1);
        } elseif($direction === 'dislike-down') {
            // Un-dislike book
            $dislikedBook = $dislikedBookRepository->findOneBy([
                'user' => $user,
                'book' => $book
            ]);
            $entityManager->remove($dislikedBook);
            $book->setDislikes($book->getDislikes() - 1);
        } else {
            //In case of malicious data, redirect to book page without voting on book
            return $this->redirectToRoute('book', [
                'book_id' => $book_id
            ]);
        }

        $entityManager->flush();
        return $this->redirectToRoute('book', [
            'book_id' => $book_id
        ]);
    }

    #[Route('/book/{book_id}/follow', name: 'book_follow', methods: ['POST'])]
    public function follow
    ( FollowedBookRepository $followedBookRepository,
        BookRepository $bookRepository, Request $request, EntityManagerInterface $entityManager, $book_id = null

    ) : Response
    {
        // Fetch user
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $book = $bookRepository->findOneBy(['id' => $book_id]);

        // Update followed books
        $direction = $request->request->get('follow_direction');
        if($direction === 'follow-up') {
            $followedBook = new FollowedBook($user, $book);
            $entityManager->persist($followedBook);
        } elseif($direction === 'follow-down') {
            $followedBook = $followedBookRepository->findOneBy([
                'user' => $user,
                'book' => $book
            ]);
            $entityManager->remove($followedBook);
        } else {
            // In case of malicious data, redirect to book page without following book
            return $this->redirectToRoute('book', [
                'book_id' => $book_id
            ]);
        }
        $entityManager->flush();
        return $this->redirectToRoute('book', [
            'book_id' => $book_id
        ]);
    }


    #[Route('/welcome', name: 'welcome')]
    public function welcome(AuthenticationUtils $authenticationUtils): Response
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
    //log out needs no real route, happens through security and rout .yaml files

    #[Route('/home', name: 'home')]
    public function home(LikedGenreRepository   $likedGenreRepository,
                         UserRepository         $userRepository, GenreRepository $genreRepository, BookRepository $bookRepository,
                         FollowedBookRepository $followedBookRepository): Response
    {
        // Fetch user
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        /** @var User $user*/
        $user = $this->getUser();
        $user_id = $user->getId();
        $stylesheets = ['home.css'];
        if ($user_id) {
            $user = $userRepository->findOneBy(['id' => $user_id]);
//            $books = $bookRepository->findAll();
            $liked_genre_id = $likedGenreRepository->findBy(['user'=>$user_id]);
            $genre_id = [];
            foreach ($liked_genre_id as $liked){
                $current_genre_id = $liked->getGenre()->getId();
                $genre_id[] = $current_genre_id;
            }
            $genres = $genreRepository->findBy(['id'=>$genre_id, ]);
            $genre_books =  $bookRepository->findBy(['genre'=>$genre_id] );
            $followed = $followedBookRepository->findBy(['user'=>$user_id]);
            $followed_book_id =[];
            foreach ($followed as $follow){
                $current_book_id = $follow->getBook()->getId();
                $followed_book_id[] = $current_book_id;
            }
            $followed_books = $bookRepository->findBy(['id'=>$followed_book_id]);
            $followed_authors = [];
            $followed_authors_id = [];

            foreach ($followed_books as $followed_book){
                $current_author = $followed_book->getAuthor();
                if(in_array($current_author->getID(), $followed_authors_id) ==0){
                    $followed_authors[] = $current_author;
                    $followed_authors_id[] = $current_author->getId();
                }
            }

            $books_from_authors = $bookRepository->findBy(['author' => $followed_authors]);

            $popular_books = $bookRepository->findPopular();


            shuffle($books_from_authors);
            shuffle($genre_books);
            return $this->render('home.html.twig', [
                'title' => 'Home!',
                'stylesheets' => $stylesheets,
                'user' => $user,
                'books' => $books_from_authors,
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

    #[Route('/profile', name: 'profile')]
    public function profile(BookRepository $bookRepository, FollowedBookRepository $followedBookRepository, UserRepository $userRepository, LikedBookRepository $likedBookRepository, DislikedBookRepository $dislikedBookRepository): Response {
        $stylesheets = ['profile.css'];

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        /** @var User $user*/
        $user = $this->getUser();
        $userID = $user->getId();

        if($userID) {
            $user = $userRepository->findOneBy(['id' => $userID]);
            $avatar = $user->getAvatar();


            $followed_id = $followedBookRepository->findBy(['user'=>$user]);
            $followed_book_id =[];
            foreach ($followed_id as $follow){
                $current_book_id = $follow->getBook()->getId();
                $followed_book_id[] = $current_book_id;
            }
            $follow_book = $bookRepository->findBy(['id'=>$followed_book_id]);

            $liked_id = $likedBookRepository->findBy(['user'=>$user]);
            $liked_book_id = [];
            foreach ($liked_id as $liked){
                $current_book_id = $liked->getBook()->getId();
                $liked_book_id[] = $current_book_id;
            }
            $liked_book = $bookRepository->findBy(['id'=>$liked_book_id]);

            $disliked_id = $dislikedBookRepository->findBy(['user'=>$user]);
            $disliked_book_id = [];
            foreach ($disliked_id as $disliked){
                $current_book_id= $disliked->getBook()->getId();
                $disliked_book_id[] = $current_book_id;
            }
            $disliked_book = $bookRepository->findBy(['id'=>$disliked_book_id]);

            return $this->render('profile.html.twig', [
                'user' => $user,
                'avatar' => $avatar,
                'followed_book'=> $follow_book,
                'liked_list'=> $liked_book,
                'disliked_list'=>$disliked_book,
                'stylesheets' => $stylesheets,
            ]);
        } else {
            return new Response('Error: no book title detected');
        }

    }
    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        $stylesheets = ['about.css'];
        return$this->render('about.html.twig', [
            'stylesheets'=> $stylesheets
        ]);
        }


    #[Route('/browsing', name: 'browsing') ]
    public function browsing(GenreRepository $genreRepository, BookRepository $bookRepository,
        Request $pageRequest, Request $searchRequest, Request $filterRequest, $book_title = null): Response {
        // Fetch user
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // create a form to be used to search for books
        $searchForm = $this->createForm(BookSearchFormType::class);
        // handle the search form request by calling function handleSearchFormRequest
        $bookTitle = $this->handleSearchFormRequest($searchForm, $searchRequest, $book_title);
        // if the book title is not null, then redirect to searching page
        if ($bookTitle) {
            return $this->redirectToRoute('searching', ['book_title' => $bookTitle]);
        }

        return $this->getRenderedBrowsing($genreRepository, $bookRepository, $pageRequest, $filterRequest, $searchForm, $bookTitle) ;
    }

    #[Route('/browsing/{book_title}', name: 'searching') ]
    public function searching(GenreRepository $genreRepository, BookRepository $bookRepository,
        Request $pageRequest, Request $searchRequest, Request $filterRequest, $book_title = null): Response {
        // Fetch user
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // create a form to be used to search for books
        $searchForm = $this->createForm(BookSearchFormType::class);
        // handle the request
        $bookTitle = $this->handleSearchFormRequest($searchForm, $searchRequest, $book_title);

        // declare stylesheets and javascripts to be used in the twig template
        return $this->getRenderedBrowsing($genreRepository, $bookRepository, $pageRequest, $filterRequest, $searchForm, $bookTitle);
    }

    /**
     * @param FormInterface $searchForm
     * @param Request $searchRequest
     * @param mixed $book_title
     * @return AbstractString|UnicodeString|null
     */
    private function handleSearchFormRequest(FormInterface $searchForm, Request $searchRequest, mixed $book_title): AbstractString|UnicodeString|null
    {
        // handle the request
        $searchForm->handleRequest($searchRequest);
        // if the form is submitted and valid
        if($searchForm->isSubmitted() && $searchForm->isValid()) {
            // get the data from the form
            $data = $searchForm->getData();
            // get the value from the data
            $book_title = $data->getTitle();
        }
        // if a book title is passed in the url, then get all books with that title
        return $book_title? u(str_replace(['-','=','%',':',';',',','*','+','"'],' ',$book_title))->title(true) : null;
    }


    /**
     * @param FormInterface $filterForm
     * @param Request $filterRequest
     * @param mixed $genre_ids
     * @param mixed $genre_genres
     * @return array
     */
    private function handleFilterFormRequest(FormInterface $filterForm, Request $filterRequest, mixed $genre_ids, mixed $genre_genres): array
    {
        // handle the request
        $filterForm->handleRequest($filterRequest);
        // if the form is submitted and valid
        if($filterForm->isSubmitted() && $filterForm->isValid()) {
            // get the selected choices from the form
            $data = $filterForm->get('genre')->getData();
            // if the genre ids is not null
            if($data) {
                // loop through each genre object in the data array
                foreach($data as $genre) {
                    // append the genre id to the genre ids array
                    $genre_ids[] = $genre->getId();
                    // append the genre name to the genre genres array
                    $genre_genres[] = $genre->getGenre();
                }
            }
        }
        return [$genre_ids, $genre_genres];
    }

    /**
     * @param Request $pageRequest
     * @param mixed $book_title
     * @param mixed $genre_ids
     * @param mixed $genre_genres
     * @return array
     */
    private function handelPageRequest(Request $pageRequest, mixed $book_title, mixed $genre_ids, mixed $genre_genres): array
    {
        // if pageRequest is submitted and valid, get the book_title, offset, and genre_ids from the url
        if($pageRequest->isMethod('GET') && $pageRequest->query->get('offset') != null) {
            // check if request has key 'book_title'
            if(array_key_exists('book_title', $pageRequest->query->all())) {
                // get the book title from the url
                $book_title = $pageRequest->query->get('book_title');
            }
            // check if request has key 'genre_ids'
            if(array_key_exists('genre_ids', $pageRequest->query->all())) {
                // get the genre ids from the url
                $genre_ids = $pageRequest->query->all()['genre_ids'];
                $genre_genres = $pageRequest->query->all()['genre_genres'];
            }
        }
        return [$book_title, $genre_ids, $genre_genres];
    }

    /**
     * @param GenreRepository $genreRepository
     * @param BookRepository $bookRepository
     * @param Request $pageRequest
     * @param Request $filterRequest
     * @param FormInterface $searchForm
     * @param AbstractString|UnicodeString|null $bookTitle
     * @return Response
     */
    private function getRenderedBrowsing(GenreRepository $genreRepository, BookRepository $bookRepository, Request $pageRequest, Request $filterRequest, FormInterface $searchForm, AbstractString|UnicodeString|null $bookTitle): Response
    {
        $offset = max(0, $pageRequest->query->getInt('offset'));
        // genreRepository is used to get all genres from the database for the filter form
        $bookGenres = $genreRepository->findAll();
        // if a book title is null, then get all books will be returned
        $booksPAG = $bookRepository->findAllByTitle($bookTitle, $offset);

        $genre_ids = [];
        $genre_genres = [];
        // create a form to be used to filter books
        $filterForm = $this->createForm(BookFilterFormType::class);
        // handle the request
        [$genre_ids, $genre_genres] = $this->handleFilterFormRequest($filterForm, $filterRequest, $genre_ids, $genre_genres);

        // if there is page request, then get the book title, offset, and genre ids from the url
        [$bookTitle, $genre_ids, $genre_genres] = $this->handelPageRequest($pageRequest, $bookTitle, $genre_ids, $genre_genres);

        // filter the books by the genre ids
        $books = $bookRepository->filterByGenre($booksPAG, $genre_ids, $offset);
        // get the length of the books array
        $booksCount = count($books);

        $stylesheets = ['browsing.css'];
        $javascripts = ['browsing.js'];

        // add flash message based on if the book title is null or not
        if($bookTitle) {
            // if genre ids is not empty, then add flash message with the book title and genre ids
            if(!empty($genre_ids)) {
                $this->addFlash('search', $booksCount . ' results for ' . $bookTitle . ' and genre(s) ' . implode(', ', $genre_genres));
            } else {
                $this->addFlash('search', $booksCount . ' results for ' . $bookTitle);
            }
        } else {
            // if genre ids is not empty, then add flash message with the book title and genre ids
            if(!empty($genre_ids)) {
                $this->addFlash('search', $booksCount . ' results for genre(s) ' . implode(', ', $genre_genres));
            } else {
                $this->addFlash('search', $booksCount . ' results for all books');
            }
        }

        return $this->render('browsing.html.twig', [
            'title' => 'Browser',
            'stylesheets' => $stylesheets,
            'genres' => $bookGenres,
            'books' => $books,
            'book_title' => $bookTitle,
            'searchForm' => $searchForm->createView(),
            'filterForm' => $filterForm->createView(),
            'genre_ids' => $genre_ids,
            'genre_genres' => $genre_genres,
            'previous' => $offset - BookRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($books), $offset + BookRepository::PAGINATOR_PER_PAGE),
            'books_count' => $booksCount,
            'javascripts' => $javascripts
        ]);

    }




}
