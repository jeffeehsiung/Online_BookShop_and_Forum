<?php

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\GenreRepository;
use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class BookableControllerTest extends TestCase
{

    /**
     * @depends testWelcome
     */
    public function testSettings()
    {
        $client = static::createClient();
        $client->request('GET', '/settings');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('.title', 'Settings');
        // Add more assertions based on the expected behavior of the settings route
    }

    /**
     * @depends testWelcome
     */
    public function testBook()
    {
        $client = static::createClient();
        $client->request('GET', '/book/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('.title', 'Book Title');
        // Add more assertions based on the expected behavior of the book route
    }


    /**
     * @depends testBook
     */
    public function testVote()
    {
        $client = static::createClient();
        $client->request('POST', '/book/1/vote');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        // Add more assertions based on the expected behavior of the vote route
    }

    /**
     * @depends testBook
     */
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

    /**
     * @depends testWelcome
     */
    public function testHome()
    {
        $client = static::createClient();
        $client->request('GET', '/home');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('.title', 'Home');
        // Add more assertions based on the expected behavior of the home route
    }

    /**
     * @depends testWelcome
     */
    public function testProfile()
    {
        $client = static::createClient();
        $client->request('GET', '/profile/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('.title', 'Profile');
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
        $this->assertSelectorTextContains('.title', 'About');
        // Add more assertions based on the expected behavior of the about route
    }

    /**
     * @depends testWelcome
     */
    public function testBrowsing()
    {
        // Mock the dependencies
        $genreRepositoryMock = $this->createMock(GenreRepository::class);
        $bookRepositoryMock = $this->createMock(BookRepository::class);
        $pageRequest = $this->createMock(Request::class);
        $searchRequest = Request::create('/browsing/harry', 'GET');
        $filterRequest = $this->createMock(Request::class);
        $book_title = 'Book Title';



        $bookRepositoryMock->expects($this->once())
            // call the findAllByTitle() method with the string 'Book Title' and the offset 0 as arguments
            ->method('findAllByTitle')
            ->with($book_title, 0)
            // expect the method to return a paginator object
            ->willReturn($this->createMock(\Doctrine\ORM\Tools\Pagination\Paginator::class));

        // Set up the expectations
        $genreRepositoryMock->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

    }
}
