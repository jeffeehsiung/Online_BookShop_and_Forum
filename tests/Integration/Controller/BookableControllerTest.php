<?php

namespace App\Tests\Integration\Controller;

use App\Entity\User;
use Doctrine\ORM\Tools\DebugUnitOfWorkListener;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookableControllerTest extends WebTestCase
{
    public function authenticateUser($email, $password)
    {
        /*
         * Functionality gets tested in the testWelcome, since authentication happens there
         */
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
        $client = $this->authenticateUser('hometest@test.com', 'password');
        $crawler = $client->request('GET', '/home');
        $this->assertSelectorTextContains('title', 'Home');
    }
    /**
     * @depends testHome
     */
    public function testSettings()
    {
        $client = $this->authenticateUser("test@test.com","password");
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
        //we should be automatically redirected to the welcome page (status code 302)
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Since authentication is already done, you should see the page');
        //make sure we are on welcome page
        $this->assertSelectorTextContains('title', 'The Hunger Games');
        $this->assertSelectorTextContains('h2', 'Suzanne Collins');
    }


    /**
     * @depends testBook
     */
    public function testVote()
    {
        $client = $this->authenticateUser();
        $client->request('GET', '/book/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $client->request('POST', '/book/1/vote');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertSelectorTextContains('title', 'Games');
        $this->assertSelectorTextContains('h2', 'Suzanne Collins');
    }

    /**
     * @depends testBook
     */
    public function testFollow()
    {
        $client = $this->authenticateUser();
        $client->request('GET', '/book/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $client->request('POST', '/book/1/follow');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertSelectorTextContains('title', 'The Hunger Games');
        $this->assertSelectorTextContains('h2', 'Suzanne Collins');


        // Add more assertions based on the expected behavior of the follow route
    }


    /**
     * @depends testHome
     */
    public function testProfile1()
    {
        /* First Profile is a new porfile wiht no liked bookes or profile picture nor correct username*/
        $client = $this->authenticateUser("test@test.com","password");
        $client->request('GET', '/profile');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        print_r($client->getResponse()->getContent());

        $crawler = $client->request('GET', '/profile');

        $this->assertSelectorTextContains('title', 'Profile');
        $this->assertSelectorTextContains('h1.section-title', 'Followed Books');

        //test if profile name is correct: ok
        $this->assertSelectorTextContains('h1.profilename',  "user 1007");

        //test if  avatar is correct:
        $avatarUrl = $crawler->filter('img#profilepic')->attr('src');
        $expectedAvatarUrl = 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png';
        $this->assertSame($expectedAvatarUrl, $avatarUrl);


        //Testing Followed Books
        // Assert that no folllowed books are displayed
        $this->assertSelectorTextContains('#followed div.empty', 'No followed books yet...');


        //Testing Liked Books
        // Assert that no liked books are displayed
        $this->assertSelectorTextContains('#Liked div.empty', 'No liked books yet...');


        //Testing Disliked books
        // Assert that no disliked books are displayed
        $this->assertSelectorTextContains('#Disliked div.empty', 'No disliked books yet...');




    }
    public function testProfile2()
    {
        $client = $this->authenticateUser("MATH@gmail.com","password");
        $client->request('GET', '/profile');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        print_r($client->getResponse()->getContent());

        $crawler = $client->request('GET', '/profile');

        $this->assertSelectorTextContains('title', 'Profile');
        $this->assertSelectorTextContains('h1.section-title', 'Followed Books');

        //test if profile name is correct: ok
        $this->assertSelectorTextContains('#First',  "Mathilde");
        $this->assertSelectorTextContains('#Last',  "Blanc");
        //test if  avatar is correct:
        $avatarUrl = $crawler->filter('img#profilepic')->attr('src');
        $expectedAvatarUrl = 'https://api.dicebear.com/6.x/personas/svg?seed=Angel';
        $this->assertSame($expectedAvatarUrl, $avatarUrl);


        // Assert the followed books
        $followedBooks = $client->getCrawler()->filter('#followed .media-element');
        $this->assertCount(2, $followedBooks); // Assuming there are 2 followed books for the first profile

        // Assert the titles of the followed books
        $expectedTitles = ['Cinder (The Lunar Chronicles, #1)','Little Bee']; // Assuming the followed books have these titles
        $actualTitles = [];
        $followedBooks->each(function ($book) use (&$actualTitles) {
            $actualTitles[] = $book->filter('p.title')->text();
        });

        // Assert the titles of the followed books
        $this->assertSame($expectedTitles, $actualTitles);

    }

    /**
     * @depends testWelcome
     */
    public function testAbout()
    {
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
