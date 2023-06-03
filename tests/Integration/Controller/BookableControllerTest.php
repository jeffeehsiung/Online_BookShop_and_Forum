<?php

namespace App\Tests\Integration\Controller;

use App\Entity\User;
use Doctrine\ORM\Tools\DebugUnitOfWorkListener;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookableControllerTest extends WebTestCase
{
    public function authenticateUser($username="test@test.com", $password="password")
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
        $form['_username'] = $username;
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
        $crawler = $client->request('GET', '/welcome');
        //we should be automatically redirected to the welcome page (status code 302)
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'you should have been redirected to the welcome page');
//        //follow the redirect
//        $crawler = $client->followRedirect();
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
        // follow the redirect
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
        // follow the redirect
        $crawler = $client->followRedirect();
        print_r($client->getResponse()->getContent());

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Since authentication is already done, you should see the page');
        $this->assertSelectorTextContains('title', 'Browsing', 'The title of the page should be "Browsing"');

        // Check if there is at least one browsing-list-item
        $this->assertGreaterThan(0, $crawler->filter('div.browsing-list-item')->count(), 'There should be at least one browsing-list-item');

        // check if there is a form with id search form
        $this->assertGreaterThan(0, $crawler->filter('form#search_form')->count(), 'There should be a form with id search_form');
        // check if there is a form with id genre_filter_form
        $this->assertGreaterThan(0, $crawler->filter('form#genre_filter_form')->count(), 'There should be a form with id genre_filter_form');
        //chekc if there are three buttons with class main-button: search, reset, next
        $this->assertEquals(3, $crawler->filter('button.main-button')->count(), 'There should be three buttons with class main-button');
    }

    /**
     * @depends testBrowsing
     */
    public function testSearching(){
        // get the user authenticated client
        $client = $this->authenticateUser();
        // get the browsing page
        $crawler = $client->request('GET', '/browsing');
        // get the search form
        $form = $crawler->filter('form#search_form')->form();
        // fill in the form
        $form['search'] = "Hunger";
        // select the search button
        $search_btn = $crawler->selectLink('search-btn')->link();
        // submit the form
        $crawler = $client->click($search_btn);
        // check if the page is redirected to the browsing page with a flash message containing the searched book title
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Since form is submitted, you should see the search page');
        // follow the redirect
        $crawler = $client->followRedirect();
        $this->assertSelectorTextContains('div.search-alert', 'Hunger', 'The flash message should contain the searched book title');
        // filter the collection of books by the genre_filter_form
        $form = $crawler->filter('form#genre_filter_form')->form();
        // tick the checkbox with label "Young Adult" value 17
        $form['genre_filter_form[genres][17]']->tick(['17']);
        // check if the page is redirected and has status code 200
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Since form is submitted, you should see the search page');
    }
}
