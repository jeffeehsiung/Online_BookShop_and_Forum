<?php

namespace App\Tests\Integration\Controller;

use App\Entity\User;
use Doctrine\ORM\Tools\DebugUnitOfWorkListener;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class BookableControllerTest extends WebTestCase
{
    public function authenticateUser()
    {
        /*
         * Functionality gets tested in the testHome, since authentication, if done correctly brings you to the home
         * page
         */
        $client = static::createClient();
        $crawler = $client->request('GET', '/home');
        $crawler = $client->followRedirect();
        //make sure we are on welcome page

        $login_button = $crawler->selectLink('Login')->link();
        $crawler = $client->click($login_button);

        //fill in the form
        $form = $crawler->filter('#login_form')->form();
        $form['_username'] = "test@test.com";
        $form['_password'] = "password";
        $client->submit($form);
        $crawler = $client->followRedirect();
        //make sure we got to the home page

        return $client;
    }
    public function testWelcome()
    {
        $client = static::createClient();
        $client->request('GET', '/welcome');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('title', 'Welcome');
        // Add more assertions based on the expected behavior of the welcome route
    }
    /**
     * @depends testWelcome
     */
    public function testHome()
    {
        /*
         * test that doesn't use the testAuthentication, doubles as test for authentication
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
        print_r($client->getResponse()->getContent());

        $crawler = $client->followRedirect();
        //make sure we got to the home page
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('title', 'Home');
        $this->assertSelectorTextContains('h1', 'Recommended books for you!');
        return $crawler;
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

        //test if profile name is correct: ok
        $this->assertSelectorTextContains('h1.profilename',  "user 1007");
        //test if profile name if avatar is correct:
        $this->assertSelectorTextContains('#profilepic', "https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png");

        // Add more assertions based on the expected behavior of the profile route


        //is teh name correct

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
        // Create a new client to browse the application
        $client = $this->authenticateUser();
//        $crawler = $client->request('GET', '/welcome');
        $crawler = $client->request('GET', '/browsing');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//        // log in
//        $form = $crawler->selectButton('Submit')->form();
//        $form['_username'] = 'jeffee@gmail.com';
//        $form['_password'] = 'jeffee';
//        $client->submit($form);
//
//         print_r($client->getResponse()->getContent());

//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//        $this->assertSelectorTextContains('title', 'Browsing');
//
//        // Check if book details are displayed correctly
//        $this->assertCount(1, $crawler->filter('.book-title'));
//        $this->assertEquals('Book Title', $crawler->filter('.book-title')->text());
//        $this->assertCount(1, $crawler->filter('.book-author'));
//        $this->assertEquals('Book Author', $crawler->filter('.book-author')->text());
//        // Add more assertions based on the expected book details
//
//        // Check if related books are displayed correctly
//        $this->assertCount(3, $crawler->filter('.related-book'));
//        // Add more assertions based on the expected related books
//
//        // Check if book categories are displayed correctly
//        $this->assertCount(2, $crawler->filter('.category'));
//        // Add more assertions based on the expected categories
//
//        // Check if book reviews are displayed correctly
//        $this->assertCount(2, $crawler->filter('.book-review'));
//        // Add more assertions based on the expected reviews
    }
}
