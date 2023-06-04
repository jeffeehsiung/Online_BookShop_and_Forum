<?php

namespace App\Tests\Integration\Controller;

use App\Entity\Book;
use App\Entity\DislikedBook;
use App\Entity\FollowedBook;
use App\Entity\LikedBook;
use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\DislikedBookRepository;
use App\Repository\FollowedBookRepository;
use App\Repository\LikedBookRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\Tools\DebugUnitOfWorkListener;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookableControllerTest extends WebTestCase
{

    public function authenticateUser($email="test@test.com", $password="password")
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
    public function testWelcome()
    {
        /*
          * test authentication with correct credentials
          */
        self::ensureKernelShutdown();
        $client = static::createClient();
        $crawler = $client->request('GET', '/home');
        //we should be automatically redirected to the welcome page (status code 302)
        $this->assertEquals(302, $client->getResponse()->getStatusCode(), 'you should have been redirected to the welcome page');
        //follow the redirect
        $crawler = $client->followRedirect();
        //make sure we are on welcome page
        $this->assertSelectorTextContains('title', 'Welcome');

        //open the popup
        $login_button = $crawler->selectLink('Login')->link();
        $crawler = $client->click($login_button);
        //make sure it is opened
        $this->assertSelectorTextContains('p', 'Log into your account');

        //fill in the form
        $form = $crawler->filter('#login_form')->form();
        $form['_username'] = "test@test.com";
        $form['_password'] = "password";
        $client->submit($form);
        $crawler = $client->followRedirect();
        //make sure we went to Home
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('title', 'Home');
        $this->assertSelectorTextContains('h1', 'Recommended books for you!');
    }

    public function testWelcomeWrongEmail()
    {
        /*
         * test with wrong email
         */
        self::ensureKernelShutdown();
        $client = static::createClient();
        $crawler = $client->request('GET', '/home');
        //we should be automatically redirected to the welcome page (status code 302)
        $this->assertEquals(302, $client->getResponse()->getStatusCode(), 'you should have been redirected to the welcome page');
        //follow the redirect
        $crawler = $client->followRedirect();
        //make sure we are on welcome page
        $this->assertSelectorTextContains('title', 'Welcome');

        //open the popup
        $login_button = $crawler->selectLink('Login')->link();
        $crawler = $client->click($login_button);
        //make sure it is opened
        $this->assertSelectorTextContains('p', 'Log into your account');

        //fill in the form
        $form = $crawler->filter('#login_form')->form();
        $form['_username'] = "wrong@test.com";
        $form['_password'] = "password";
        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        //make sure we remained on welcome
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('title', 'Welcome');
        print_r($client->getResponse()->getContent());

        $this->assertSelectorTextContains('#error_display', 'Invalid credentials.');
    }

    public function testWelcomeWrongPassword()
    {
        /*
         * test with wrong password
         */
        self::ensureKernelShutdown();
        $client = static::createClient();
        $crawler = $client->request('GET', '/home');
        //we should be automatically redirected to the welcome page (status code 302)
        $this->assertEquals(302, $client->getResponse()->getStatusCode(), 'you should have been redirected to the welcome page');
        //follow the redirect
        $crawler = $client->followRedirect();
        //make sure we are on welcome page
        $this->assertSelectorTextContains('title', 'Welcome');

        //open the popup
        $login_button = $crawler->selectLink('Login')->link();
        $crawler = $client->click($login_button);
        //make sure it is opened
        $this->assertSelectorTextContains('p', 'Log into your account');

        //fill in the form
        $form = $crawler->filter('#login_form')->form();
        $form['_username'] = "test@test.com";
        $form['_password'] = "wrongPassword";
        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        //make sure we remained on welcome
        //$this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('title', 'Welcome');
        $this->assertSelectorTextContains('#error_display', 'Invalid credentials.');
    }


    /**
     * @depends testWelcome
     */
    public function testHome()
    {
        $client = $this->authenticateUser();
        $crawler = $client->request('GET', '/home');
        $this->assertSelectorTextContains('title', 'Home');
    }
    /**
     * @depends testHome
     */
    public function testSettings()
    {
        $client = $this->authenticateUser();
        $crawler = $client->request('GET', '/home');
        $this->assertSelectorTextContains('title', 'Home');

        //based on position in the list, will change if we change the order of the elements in the list
        $linkPosition = 2;
        $link = $crawler->filter('a.nav-link')->eq($linkPosition);
        $crawler = $client->click($link->link());
        //check what got returned
        print_r($client->getResponse()->getContent());
        //make some asserts to make sure all got displayed well
        $this->assertSelectorTextContains('title', 'Settings');
        $this->assertSelectorTextContains('h2', 'Edit you profile:');

    }

    /**
     * @depends testHome
     */
    public function testBook()
    {
        $client = $this->authenticateUser();
        $client->request('GET', '/book/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Since authentication is already done, you should see the page');
        $this->assertSelectorTextContains('title', 'The Hunger Games');
        $this->assertSelectorTextContains('h2', 'Suzanne Collins');
    }


    /**
     * @depends testBook
     * @throws \Exception
     */
    public function testVote()
    {
        // Alternative login method
        self::ensureKernelShutdown();
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'test@test.com']);
        $client->loginUser($testUser);

        // get a book and the amount of initial likes to be tested
        $bookRepository = static::getContainer()->get(BookRepository::class);
        $book = $bookRepository->findOneBy(['id' => 1]);
        $initialLikes = $book->getLikes();

        /*
         *  Correct data test 1: like
         */
        // Make a request to the vote endpoint with correct data
        $client->request(
            'POST',
            '/book/' . $book->getId() . '/vote',
            ['direction' => 'like-up']
        );

        // Assert the response status code
        $this->assertSame(302, $client->getResponse()->getStatusCode());

        // Assert that the book was liked by the user
        //TODO: refactor likedBookRepository in setup
        $likedBookRepository = static::getContainer()->get(LikedBookRepository::class);
        $likedBook = $likedBookRepository->findOneBy(['user' => $testUser, 'book' => $book]);
        $this->assertInstanceOf(LikedBook::class, $likedBook);

        // Assert that the book's likes count has been incremented
        $this->assertSame($initialLikes + 1, $book->getLikes());

        // Clean up the database
        $this->entityManager = $client->getContainer()->get('doctrine')->getManager();


        /*
         *  Correct data test 2: unlike
         */
        // Make a request to the vote endpoint with like-down
        $client->request(
            'POST',
            '/book/' . $book->getId() . '/vote',
            ['direction' => 'like-down']
        );

        // Assert the response status code
        $this->assertSame(302, $client->getResponse()->getStatusCode());

        // Re-fetch book
        $book = $bookRepository->findOneBy(['id' => 1]);

        // Assert that the book was unliked by the user
        $likedBook = $likedBookRepository->findOneBy(['user' => $testUser, 'book' => $book]);
        $this->assertNull($likedBook);

        // Assert that the book's likes count has been restored
        $this->assertSame($initialLikes, $book->getLikes());

        /*
         *  Correct data test 3: dislike
        */
        // fetch book and dislikes
        $book = $bookRepository->findOneBy(['id' => 1]);
        $initialDisLikes = $book->getDislikes();
        // Make a request to the vote endpoint with correct data
        $client->request(
            'POST',
            '/book/' . $book->getId() . '/vote',
            ['direction' => 'dislike-up']
        );
        // Assert the response status code
        $this->assertSame(302, $client->getResponse()->getStatusCode());

        // Assert that the book was disliked by the user
        //TODO: refactor disLikedBookRepository in setup
        $disLikedBookRepository = static::getContainer()->get(DisLikedBookRepository::class);
        $disLikedBook = $disLikedBookRepository->findOneBy(['user' => $testUser, 'book' => $book]);
        $this->assertInstanceOf(DislikedBook::class, $disLikedBook);

        // Assert that the book's likes count has been incremented
        $this->assertSame($initialDisLikes + 1, $disLikedBook->getBook()->getDislikes());

        /*
         *  Correct data test 4: un-dislike
         */
        // Make a request to the vote endpoint with like-down
        $client->request(
            'POST',
            '/book/' . $book->getId() . '/vote',
            ['direction' => 'dislike-down']
        );

        // Assert the response status code
        $this->assertSame(302, $client->getResponse()->getStatusCode());

        // Re-fetch book
        $book = $bookRepository->findOneBy(['id' => 1]);

        // Assert that the book was unliked by the user
        $disLikedBook = $disLikedBookRepository->findOneBy(['user' => $testUser, 'book' => $book]);
        $this->assertNull($likedBook);

        // Assert that the book's likes count has been restored
        $this->assertSame($initialDisLikes, $book->getDislikes());


        /*
         *   Malicious data test
         */
        // Re-fetch book
        $book = $bookRepository->findOneBy(['id' => 1]);
        // Make a request to the vote endpoint with malicious data
        $client->request(
            'POST',
            '/book/' . $book->getId() . '/vote',
            ['direction' => 'malicious-data']
        );

        // Assert the response status code
        $this->assertSame(302, $client->getResponse()->getStatusCode());

        // Re-fetch book
        $book = $bookRepository->findOneBy(['id' => 1]);

        // Assert that the book was not liked by the user
        $likedBook = $likedBookRepository->findOneBy(['user' => $testUser, 'book' => $book]);
        $this->assertNull($likedBook);

        // Assert that the likes count remained the same
        $this->assertSame($initialLikes, $book->getLikes());
    }

    /**
     * @depends testBook
     */
    public function testFollow()
    {
        // Alternative login method
        //TODO: implement in setUp type function
        self::ensureKernelShutdown();
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'test@test.com']);
        $client->loginUser($testUser);

        // get a book to be tested
        $bookRepository = static::getContainer()->get(BookRepository::class);
        $book = $bookRepository->findOneBy(['id' => 1]);

        /*
         * Correct data test
         */
        // Make a request to the follow endpoint
        $client->request(
            'POST',
            '/book/' . $book->getId() . '/follow',
            ['follow-direction' => 'follow-up']
        );

        // Assert the response status code
        $this->assertSame(302, $client->getResponse()->getStatusCode());

        // Assert that the book is followed by the user
        //TODO: refactor followedBookRepository in setup
        $followedBookRepository = static::getContainer()->get(FollowedBookRepository::class);
        $followedBook = $followedBookRepository->findOneBy(['user' => $testUser, 'book' => $book]);
        $this->assertInstanceOf(FollowedBook::class, $followedBook);

        // Clean up the database
        $this->entityManager = $client->getContainer()->get('doctrine')->getManager();
        $this->entityManager->remove($followedBook);
        $this->entityManager->flush();

        /*
         * Malicious data test
         */
        // Make a request to the follow endpoint with malicious data
        $client->request(
            'POST',
            '/book/' . $book->getId() . '/follow',
            ['follow-direction' => 'malicious-data']
        );

        // Assert the response status code
        $this->assertSame(302, $client->getResponse()->getStatusCode());

        // Assert that the book is not followed by the user
        //TODO: refactor followedBookRepository in setup
        $followedBook = $followedBookRepository->findOneBy(['user' => $testUser, 'book' => $book]);
        $this->assertNull($followedBook);
    }


    /**
     * @depends testHome
     */
    public function testProfile()
    {
        $client = $this->authenticateUser();
        $client->request('GET', '/profile');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        print_r($client->getResponse()->getContent());
        $this->assertSelectorTextContains('title', 'Profile');
        $this->assertSelectorTextContains('h1.section-title', 'Followed Books');
        // Add more assertions based on the expected behavior of the profile route
    }

    /**
     * @depends testWelcome
     */
    public function testAbout()
    {
        self::ensureKernelShutdown();
        $client = static::createClient();
        $client->request('GET', '/about');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('title', 'About');
        // Add more assertions based on the expected behavior of the about route
    }

    /**
     * @depends testWelcome
     */
    public function testBrowsing()
    {
        $client = $this->authenticateUser();
        // follow the redirect
        $crawler = $client->request('GET', '/browsing');
//        // follow the redirect
//        print_r($client->getResponse()->getContent());
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Since authentication is already done, you should see the page');
        $this->assertSelectorTextContains('title', 'Browsing', 'The title of the page should be "Browsing"');

        // Check if there is at least one browsing-list-item
        $this->assertGreaterThan(0, $crawler->filter('div.browsing-list-item')->count(), 'There should be at least one browsing-list-item');

        // check if there is a form with id search form
        $this->assertGreaterThan(0, $crawler->filter('#book_search_form_title')->count(), 'There should be a form with id search_form');
        // check if there is a form with id genre_filter_form
        $this->assertGreaterThan(0, $crawler->filter('#book_filter_form_genre')->count(), 'There should be a form with id genre_filter_form');
        //chekc if there are three buttons with class main-button: search, reset, next
        $this->assertEquals(3, $crawler->filter('.main-button')->count(), 'There should be three buttons with class main-button');
    }

    /**
     * @depends testBrowsing
     */
    public function testSearching(){
        // get the user authenticated client
        $client = $this->authenticateUser();
        // get the browsing page
        $crawler = $client->request('GET', '/browsing');
        // get the first form in the page
        $form = $crawler->filter('form')->form();
        // set the value of the input with id book_search_form_title to "Hunger"
        $form['book_search_form[title]'] = 'Harry';
        // submit the form
        $crawler = $client->submit($form);
        // follow the redirect
        $crawler = $client->followRedirect();
        // check if the page is redirected to the browsing page with a flash message containing the searched book title
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Since form is submitted, you should see the search page');
        $this->assertSelectorTextContains('div.search-alert', 'Harry', 'The flash message should contain the searched book title');
        $filter_form = $crawler->filter('aside')->filter('form')->form();
        // get the input checkbox type with value 17
        $genre_form = $crawler->filter('aside')->filter('form')->form()['book_filter_form[genre]'];
//        $genre_checkbox = $crawler->filter('aside')->filter('form')->filter('input[type="checkbox"][value="17"]');
        // filter and list all input checkbox type
        $genre_checkbox = $crawler->filter('aside')->filter('form')->filter('input[type="checkbox"]');
        // assert if there is 17 input checkbox type ( 17 genres)
        $this->assertEquals(17, $genre_checkbox->count(), 'There should be 17 input checkbox type');
        // get the last DOM element
        $YoungAdult = $genre_checkbox->getNode(16);
        // assert if the type of the last DOM element is checkbox
        $this->assertEquals('checkbox', $YoungAdult->getAttribute('type'), 'The type of the last DOM element should be checkbox');
        // tick the last checkbox
        $genre_form[16]->tick();
        // check if the last checkbox is checked
        $this->assertTrue($genre_form[16]->hasValue(), 'The last checkbox should be checked');
        // submit the form
        $crawler = $client->submit($filter_form);
        // check the flash message
        $this->assertSelectorTextContains('div.search-alert', 'Harry', 'The flash message should contain the searched book title');
        // test the reset button
        $reset_btn = $crawler->filter('#reset-btn')->link();
        // click the reset button
        $crawler = $client->click($reset_btn);
        // check the redirect status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Since reset button is clicked, you should see the search page');
    }
}
