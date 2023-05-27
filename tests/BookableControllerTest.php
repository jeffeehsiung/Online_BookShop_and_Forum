<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\GenreRepository;
use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class BookableControllerTest extends WebTestCase
{

    public function testSettings()
    {
        $client = static::createClient();
        $client->request('GET', '/settings');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('.title', 'Settings');
        // Add more assertions based on the expected behavior of the settings route
    }

    public function testBook()
    {
        $client = static::createClient();
        $client->request('GET', '/book/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('.title', 'Book Title');
        // Add more assertions based on the expected behavior of the book route
    }

    public function testVote()
    {
        $client = static::createClient();
        $client->request('POST', '/book/1/vote');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        // Add more assertions based on the expected behavior of the vote route
    }

    public function testFollow()
    {
        $client = static::createClient();
        $client->request('POST', '/book/1/follow');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        // Add more assertions based on the expected behavior of the follow route
    }

    public function testWelcome()
    {
        $client = static::createClient();
        $client->request('GET', '/welcome');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('.title', 'Welcome!');
        // Add more assertions based on the expected behavior of the welcome route
    }

    public function testHome()
    {
        $client = static::createClient();
        $client->request('GET', '/home');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('.title', 'Home');
        // Add more assertions based on the expected behavior of the home route
    }

    public function testProfile()
    {
        $client = static::createClient();
        $client->request('GET', '/profile/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('.title', 'Profile');
        // Add more assertions based on the expected behavior of the profile route
    }

    public function testAbout()
    {
        $client = static::createClient();
        $client->request('GET', '/about');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('.title', 'About');
        // Add more assertions based on the expected behavior of the about route
    }

    public function testBrowsing()
    {
        // Create a new client to browse the application
        $client = static::createClient();
        $crawler = $client->request('GET', '/welcome');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        // log in
        $form = $crawler->selectButton('Submit')->form();
        $form['_username'] = 'jeffee@gmail.com';
        $form['_password'] = 'jeffee';
        $client->submit($form);

         print_r($client->getResponse()->getContent());

//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//        $this->assertSelectorTextContains('.title', 'Browsing');
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
