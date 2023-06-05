<?php

namespace App\Tests\Integration\Controller;

use App\Entity\User;
use App\Repository\AvatarRepository;
use App\Repository\BookRepository;
use App\Repository\FollowedBookRepository;
use App\Repository\GenreRepository;
use App\Repository\LikedGenreRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use function PHPUnit\Framework\assertContains;

class HomeControllerTest extends WebTestCase
{
    public function authenticateUser($email="test@test.com", $password="password"):KernelBrowser
    {
        /*
         * Functionality gets tested in the testWelcome, since authentication happens there
         */
        self::ensureKernelShutdown();
        $client = static::createClient();
        $crawler = $client->request('GET', '/home');
        $crawler = $client->followRedirect();
        //make sure we are on welcome page
        $login_button = $crawler->selectLink('Login')->link();
        $crawler = $client->click($login_button);

        //fill in the form
        $form = $crawler->filter('#login_form')->form();
        $form['_username'] = $email;
        $form['_password'] = $password;
        $client->submit($form);
        $crawler = $client->followRedirect();

        return $client;
    }
    public function testLoadingHomePage():void
    {
        $client = $this->authenticateUser('hometest@test.com', 'password');
        $this->assertSelectorTextContains('title', 'Home');
        $this->assertSelectorTextContains('div.followed-books h3','Based on your followed books');
        $this->assertSelectorTextContains('div.trending-books h3','Trending Books');
        //these genres are loaded in through a fixture
        $this->assertSelectorTextContains('div.Fiction h3','Books in the category Fiction');
        $this->assertSelectorTextContains('div.Mystery h3','Books in the category Mystery');
        $this->assertSelectorTextContains('div.Romance h3','Books in the category Romance');
        $this->assertSelectorTextContains('div.Science.Fiction h3','Books in the category Science Fiction');
    }
    /**
     * @depends testLoadingHomePage
     */
    public function testTrendingBooks():void
    {
        $client = $this->authenticateUser('hometest@test.com', 'password');
        $bookRepository = static::getContainer()->get(BookRepository::class);
        $popularBooks = $bookRepository->findPopular();
        //make sure that the first element is the most liked book
        $this->assertSelectorTextContains('div.trending-books h4', $popularBooks[0]->getTitle());
        //make sure that all books are loaded in correctly
        foreach ($popularBooks as $popularBook)
        {
            $this->assertSelectorExists('div.trending-books h4', $popularBook->getTitle());
            print_r($popularBook->getTitle());
            $this->assertSelectorExists('div.trending-books h4', $popularBook->getAuthor()->getAuthorName());
        }
        $trendingDisplayed = $client->getCrawler()->filter('div.trending-books div.book');
        $this->assertCount(20, $trendingDisplayed);
    }

    /**
     * @depends testLoadingHomePage
     */
    public function testGenres():void
    {
        $client = $this->authenticateUser('hometest@test.com', 'password');
        $bookRepository = static::getContainer()->get(BookRepository::class);
        $books = $client->getCrawler()->filter('div.Romance h4.title')->each(function($node){
            return $node->text();
        });
        foreach ($books as $book){
            //since titles are not unique, multiple occurrences of the same title are possible, so we need to check if one of  them matches
            $bookDbs = $bookRepository->findBy(['title' => $book]);
            //because of different character standards, some of the titles in the database won't perfectly match
            if($bookDbs != null){
                $genres = [];
                foreach ($bookDbs as $bookDb){
                    $genre = $bookDb->getGenre();
                    $genres[] = $genre->getGenre();

                }
                print_r($genres[0]);
                assertContains('Romance', $genres);
            }

        }
        $this->assertCount(20, $books);

        $books = $client->getCrawler()->filter('div.Mystery h4.title')->each(function($node){
            return $node->text();
        });
        foreach ($books as $book){
            //since titles are not unique, multiple occurrences of the same title are possible, so we need to check if one of  them matches
            $bookDbs = $bookRepository->findBy(['title' => $book]);
            if($bookDbs != null){
                $genres = [];
                foreach ($bookDbs as $bookDb){
                    $genre = $bookDb->getGenre();
                    $genres[] = $genre->getGenre();
                }
                print_r($genres[0]);
                assertContains('Mystery', $genres);
            }
        }
        $this->assertCount(20, $books);

        $books = $client->getCrawler()->filter('div.Science.Fiction h4.title')->each(function($node){
            return $node->text();
        });
        foreach ($books as $book){
            //since titles are not unique, multiple occurrences of the same title are possible, so we need to check if one of  them matches
            $bookDbs = $bookRepository->findBy(['title' => $book]);
            if($bookDbs != null){
                $genres = [];
                foreach ($bookDbs as $bookDb){
                    $genre = $bookDb->getGenre();
                    $genres[] = $genre->getGenre();
                }
                print_r($genres[0]);
                assertContains('Science Fiction', $genres);
            }
        }
        $this->assertCount(20, $books);


    }
    public function testFollowed():void
    {
        $client = $this->authenticateUser('hometest@test.com', 'password');
        $bookRepository = static::getContainer()->get(BookRepository::class);
        $displayedBooks = $client->getCrawler()->filter('div.followed-books h4.title')->each(function($node){
            return $node->text();
        });
        $followedBooksRepository = static::getContainer()->get(FollowedBookRepository::class);
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userID = $userRepository->findOneBy(['email'=>'hometest@test.com'])->getId();
        print_r('user_id is');
        print_r($userID);
        $followedBooks = $followedBooksRepository->findBy(['user' => $userID]);
        $followedAuthors = [];
        foreach ($followedBooks as $followedBook){
            $followedAuthors[] = $bookRepository->findOneBy(['id'=> $followedBook->getBook()])->getAuthor()->getAuthorName();
        }
        foreach ($displayedBooks as $book){
            //since titles are not unique, multiple occurrences of the same title are possible, so we need to check if one of  them matches
            $book = $bookRepository->findOneBy(['title' => $book]);
            $author = $book->getAuthor()->getAuthorName();
            print_r($author);

        }
        $this->assertCount(20, $displayedBooks);

    }
}
