<?php

namespace App\Tests\Integration\Controller;

use App\Entity\DislikedBook;
use App\Entity\FollowedBook;
use App\Entity\LikedBook;
use App\Repository\BookRepository;
use App\Repository\DislikedBookRepository;
use App\Repository\FollowedBookRepository;
use App\Repository\LikedBookRepository;
use App\Repository\UserRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookableControllerTest extends WebTestCase
{

    public function authenticateUser($email="test@test.com", $password="password"): KernelBrowser
    {
        /*
         * Functionality gets tested in the testWelcome, since authentication happens there
         */
        self::ensureKernelShutdown();
        $client = static::createClient();
        $client->request('GET', '/home');
        $crawler = $client->followRedirect();
        //make sure we are on welcome page
        $login_button = $crawler->selectLink('Login')->link();
        $crawler = $client->click($login_button);

        //fill in the form
        $form = $crawler->filter('#login_form')->form();
        $form['_username'] = $email;
        $form['_password'] = $password;
        $client->submit($form);
        $client->followRedirect();
        return $client;
    }

    public function testWelcome()
    {
        /*
          * test authentication with correct credentials
          */
        self::ensureKernelShutdown();
        $client = static::createClient();
        $client->request('GET', '/home');
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
        $client->followRedirect();
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
        $client->request('GET', '/home');
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
        $client->followRedirect();
        //make sure we remained on welcome
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('title', 'Welcome');

        $this->assertSelectorTextContains('#error_display', 'Invalid credentials.');
    }

    public function testWelcomeWrongPassword()
    {
        /*
         * test with wrong password
         */
        self::ensureKernelShutdown();
        $client = static::createClient();
        $client->request('GET', '/home');
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
        $client->followRedirect();
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
        $client = $this->authenticateUser('hometest@test.com');
        $client->request('GET', '/home');
        $this->assertSelectorTextContains('title', 'Home');
        $this->assertSelectorExists('div.followed_books h3','Based on your followed books');

    }
    /**
     * @depends testWelcome
     */
    public function testSettings()
    {
        $client = $this->authenticateUser();
        $crawler = $client->request('GET', '/home');
        $this->assertSelectorTextContains('title', 'Home');

        //based on position in the list, will change if we change the order of the elements in the list
        $linkPosition = 2;
        $link = $crawler->filter('a.nav_link')->eq($linkPosition);
        $client->click($link->link());
        //check what got returned
        //print_r($client->getResponse()->getContent());
        //make some asserts to make sure all got displayed well
        $this->assertSelectorTextContains('title', 'Settings');
        $this->assertSelectorTextContains('h2', 'Edit your profile:');

    }

    /**
     * @depends testWelcome
     */
    public function testBook()
    {
        $client = $this->authenticateUser();
        $client->request('GET', '/book/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Since authentication is already done, you should see the page');
        $this->assertSelectorTextContains('title', 'The Hunger Games');
        $this->assertSelectorExists('h2', 'Suzanne Collins');
    }


    /**
     * @depends testBook
     * @throws Exception
     */
    public function testVote()
    {
        // Blabla dumb comment

        // Alternative login method
        self::ensureKernelShutdown();
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'booktest@test.com']);
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
        $likedBookRepository = static::getContainer()->get(LikedBookRepository::class);
        $likedBook = $likedBookRepository->findOneBy(['user' => $testUser, 'book' => $book]);
        $this->assertInstanceOf(LikedBook::class, $likedBook);

        // Assert that the book's likes count has been incremented
        $this->assertSame($initialLikes + 1, $book->getLikes());

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

        // Assert that the book was un disliked by the user
        $disLikedBook = $disLikedBookRepository->findOneBy(['user' => $testUser, 'book' => $book]);
        $this->assertNull($disLikedBook);

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
     * @throws Exception
     */
    public function testFollow()
    {
        // Bla bla dumb comment

        // Alternative login method
        self::ensureKernelShutdown();
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'booktest@test.com']);
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
            ['follow_direction' => 'follow-up']
        );

        // Assert the response status code
        $this->assertSame(302, $client->getResponse()->getStatusCode());

        // Assert that the book is followed by the user
        $followedBookRepository = static::getContainer()->get(FollowedBookRepository::class);
        $followedBook = $followedBookRepository->findOneBy(['user' => $testUser, 'book' => $book]);
        $this->assertInstanceOf(FollowedBook::class, $followedBook);

        // Clean up the database
        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $entityManager->remove($followedBook);
        $entityManager->flush();

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
        $followedBook = $followedBookRepository->findOneBy(['user' => $testUser, 'book' => $book]);
        $this->assertNull($followedBook);
    }


    /**
     * @depends testWelcome
     */
    public function testProfile1()
    {
        /* First Profile is a new porfile wiht no liked bookes or profile picture nor correct username*/
        $client = $this->authenticateUser();
        $client->request('GET', '/profile');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $crawler = $client->request('GET', '/profile');

        $this->assertSelectorTextContains('title', 'Profile');
        $this->assertSelectorTextContains('h1.section_title', 'Followed Books');

        //test if profile name is correct: ok
        $this->assertSelectorTextContains('h2.profile_name',  "test");

        //test if  avatar is correct:
        $avatarUrl = $crawler->filter('img#profile_pic')->attr('src');
        $expectedAvatarUrl = 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png';
        $this->assertSame($expectedAvatarUrl, $avatarUrl);
        //testing bio
        $this->assertSelectorNotExists('div.bio_container');

        //Testing Followed Books
        // Assert that no folllowed books are displayed
        $this->assertSelectorTextContains('#followed div.empty', 'No followed books yet...');


        //Testing Liked Books
        // Assert that no liked books are displayed
        $this->assertSelectorTextContains('#liked div.empty', 'No liked books yet...');


        //Testing Disliked books
        // Assert that no disliked books are displayed
        $this->assertSelectorTextContains('#disliked div.empty', 'No disliked books yet...');




    }

    /**
     * @depends testWelcome
     */
    public function testProfile2()
    {
        $client = $this->authenticateUser("profiletest@test.com");
        $client->request('GET', '/profile');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $crawler = $client->request('GET', '/profile');

        $this->assertSelectorTextContains('title', 'Profile');


    //test if profile name is correct: ok
        $this->assertSelectorTextContains('#first',  "profile");
        $this->assertSelectorTextContains('#last',  "test");
        //test if  avatar is correct:
        $avatarUrl = $crawler->filter('img#profile_pic')->attr('src');
        $expectedAvatarUrl = 'https://api.dicebear.com/6.x/personas/svg?seed=Angel';
        $this->assertEquals($expectedAvatarUrl, $avatarUrl);
        $this->assertSelectorTextContains('#user_bio', "hi,I like books");
        $this->assertSelectorTextContains('h1.section_title#follow_title', 'Followed Books');
        // Assert the followed books
        $followedBooks = $client->getCrawler()->filter('#followed .media_element');
        $this->assertCount(4, $followedBooks); // Assuming there are 2 followed books for the first profile

        // Assert the titles of the followed books
        $expectedTitles = ['The Hunger Games (The Hunger Games, #1)','Twilight (Twilight, #1)','The Great Gatsby','The Hobbit'
]; // Assuming the followed books have these titles
        $actualTitles = [];
        $followedBooks->each(function ($book) use (&$actualTitles) {
            $actualTitles[] = $book->filter('p.title')->text();
        });

        // Assert the titles of the followed books
        $this->assertSame($expectedTitles, $actualTitles);




        // Assert the section title
        $this->assertSelectorTextContains('h1.section_title#like_title', 'Liked Books');

        // Assert the liked books
        $likedBooks = $client->getCrawler()->filter('#liked .media_element');
        $this->assertCount(4, $likedBooks); // Assuming there are 2 liked books for the first profile

        // Get the actual book titles from the page
        $actualTitles = [];
        $likedBooks->each(function ($book) use (&$actualTitles) {
            $actualTitles[] = $book->filter('p.title')->text();
        });

        // Assert the titles of the liked books
        $expectedTitles = ["The Hunger Games (The Hunger Games, #1)", "Harry Potter and the Sorcerer's Stone (Harry Potter, #1)", "Twilight (Twilight, #1)","To Kill a Mockingbird"]; // Adjust with the actual titles in the same order
        $this->assertSame($expectedTitles, $actualTitles);

        // test Disliked books
        $this->assertSelectorTextContains('h1.section_title#dislike_title', 'Disliked Books');

// Assert the disliked books
        $dislikedBooks = $client->getCrawler()->filter('#disliked .media_element');
        $this->assertCount(4, $dislikedBooks); // Assuming there are 3 disliked books for the first profile

// Get the actual book titles from the page
        $actualTitles = [];
        $dislikedBooks->each(function ($book) use (&$actualTitles) {
            $actualTitles[] = $book->filter('p.title')->text();
        });

// Assert the titles of the disliked books
        $expectedTitles = ['Me Talk Pretty One Day', 'Where the Wild Things Are', 'The Count of Monte Cristo', 'The Road']; // Adjust with the actual titles in any order
        $this->assertEqualsCanonicalizing($expectedTitles, $actualTitles);

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
        $this->assertGreaterThan(0, $crawler->filter('div.browsing_list_item')->count(), 'There should be at least one browsing-list-item');

        // check if there is a form with id search form
        $this->assertGreaterThan(0, $crawler->filter('#book_search_form_title')->count(), 'There should be a form with id search_form');
        // check if there is a form with id genre_filter_form
        $this->assertGreaterThan(0, $crawler->filter('#book_filter_form_genre')->count(), 'There should be a form with id genre_filter_form');
        //chekc if there are three buttons with class main-button: search, reset, next
        $this->assertEquals(3, $crawler->filter('.main_button')->count(), 'There should be three buttons with class main-button');
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
        $client->submit($form);
        // follow the redirect
        $crawler = $client->followRedirect();
        // check if the page is redirected to the browsing page with a flash message containing the searched book title
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Since form is submitted, you should see the search page');
        $this->assertSelectorTextContains('div.search_alert', 'Harry', 'The flash message should contain the searched book title');
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
        $this->assertSelectorTextContains('div.search_alert', 'Harry', 'The flash message should contain the searched book title');
        // test the reset button
        $reset_btn = $crawler->filter('#reset_btn')->link();
        // click the reset button
        $client->click($reset_btn);
        // check the redirect status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Since reset button is clicked, you should see the search page');
        // test the search bar with SQL injection
        $form['book_search_form[title]'] = 'Harry\' OR 1=1';
        // submit the form
        $client->submit($form);
        // follow the redirect
        $crawler = $client->followRedirect();
        // check if the page is redirected to the browsing page with a flash message containing the searched book title
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Since form is submitted, you should see the search page');
        $this->assertSelectorTextContains('div.search_alert', 'Harry\' OR', 'The flash message should contain the searched book title');

    }
}
