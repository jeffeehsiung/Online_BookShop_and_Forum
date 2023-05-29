<?php

namespace App\Tests\Unit;

use App\Controller\BaseController;
use App\Controller\BookableController;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use App\Repository\GenreRepository;
use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use function PHPUnit\Framework\assertIsArray;


class BookableControllerTest extends TestCase
{

    /**
     * @depends testWelcome
     */
    public function testSettings()
    {
    }

    /**
     * @depends testWelcome
     */
    public function testBook()
    {
    }


    /**
     * @depends testBook
     */
    public function testVote()
    {
    }

    /**
     * @depends testBook
     */
    public function testFollow()
    {
    }

    public function testWelcome()
    {
    }

    /**
     * @depends testWelcome
     */
    public function testHome()
    {
    }

    /**
     * @depends testWelcome
     */
    public function testProfile()
    {

    }

    /**
     * @depends testWelcome
     */
    public function testAbout()
    {
    }

    public function testBrowsing()
    {
        //workflow: given, when, then, arrange, act, assert
        // given that we have a genre repository and a book repository and three requests
        $registry = $this->createConfiguredMock(ManagerRegistry::class, [
            'getRepository' => $this->createMock(GenreRepository::class)
        ]
        );
        // assert repository is not null
        $this->assertNotNull($registry->getRepository(GenreRepository::class));
        // print the class name of the repository
        $this->assertIsString(get_class($registry->getRepository(GenreRepository::class)));
        $genreRepositoryMock = new GenreRepository($registry);
        // asser genreRepositoryMock is not null
        $this->assertNotNull($genreRepositoryMock);
        // assert that the repository is an instance of GenreRepository
        $this->assertInstanceOf(GenreRepository::class, $genreRepositoryMock);
        // declare registerMock as a ManagerRegistry for the book repository
        $registry = $this->createConfiguredMock(ManagerRegistry::class, [
            'getRepository' => $this->createMock(BookRepository::class)
        ]
        );
        // assert repository is not null
        $this->assertNotNull($registry->getRepository(BookRepository::class));
        // print the class name of the repository
        $this->assertIsString(get_class($registry->getRepository(BookRepository::class)));
        $bookRepositoryMock = new BookRepository($registry);
        // asser bookRepositoryMock is not null
        $this->assertNotNull($bookRepositoryMock);
        // assert that the repository is an instance of BookRepository
        $this->assertInstanceOf(BookRepository::class, $bookRepositoryMock);
        $pageRequest = $this->createMock(Request::class);
        $searchRequest = $this->createMock(Request::class);
        $filterRequest = $this->createMock(Request::class);
        // construct a bookable controller
        $bookableController = $this->createMock(BookableController::class);
        // assert that the bookable controller is not null
        $this->assertNotNull($bookableController);
        // assert that the bookable controller is an instance of BookableController
        $this->assertInstanceOf(BookableController::class, $bookableController);
        // given that the method will return a response, assert that the response is not null
        $response = $bookableController->browsing($genreRepositoryMock, $bookRepositoryMock, $pageRequest, $searchRequest, $filterRequest);
        $this->assertNotNull($response);
        // assert that the response is an instance of Response
        $this->assertInstanceOf(Response::class, $response);
        // assert that the response is not empty
        $this->assertNotEmpty($response);
    }
}
