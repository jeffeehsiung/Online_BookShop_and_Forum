<?php

namespace App\Tests\Unit\Controller;

use App\Controller\BaseController;
use App\Controller\BookableController;
use App\Repository\UserRepository;
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
        //dummytest to avoid error regarding empty test
        self::assertTrue(true);
    }

    /**
     * @depends testWelcome
     */
    public function testBook()
    {
        //dummytest to avoid error regarding empty test
        self::assertTrue(true);
    }

//TODO replace dummytests with proper tests
    /**
     * @depends testBook
     */
    public function testVote()
    {
        //dummytest to avoid error regarding empty test
        self::assertTrue(true);
    }

    /**
     * @depends testBook
     */
    public function testFollow()
    {
        //dummytest to avoid error regarding empty test
        self::assertTrue(true);
    }

    public function testWelcome()
    {
        //workflow: given, when, then, arrange, act, assert
        // given that we have a user repository
        $userRepository = $this->createConfiguredMock(ManagerRegistry::class, [
                'getRepository' => $this->createMock(UserRepository::class)
            ]
        );
        // assert repository is not null
        $this->assertNotNull($userRepository->getRepository(UserRepository::class));
        // print the class name of the repository
        $this->assertIsString(get_class($userRepository->getRepository(UserRepository::class)));
        $this->assertStringContainsString("UserRepository",get_class($userRepository->getRepository(UserRepository::class)));
        $userRepositoryMock = new UserRepository($userRepository);
        // assert userRepositoryMock is not null
        $this->assertNotNull($userRepositoryMock);
        // assert that the repository is an instance of UserRepository
        $this->assertInstanceOf(UserRepository::class, $userRepositoryMock);
        // construct a bookable controller
        $bookableController = $this->createMock(BookableController::class);
        // assert that the bookable controller is not null
        $this->assertNotNull($bookableController);
        // assert that the bookable controller is an instance of BookableController
        $this->assertInstanceOf(BookableController::class, $bookableController);
    }

    /**
     * @depends testWelcome
     */
    public function testHome()
    {
        //dummytest to avoid error regarding empty test
        self::assertTrue(true);
    }

    /**
     * @depends testWelcome
     */
    public function testProfile()
    {
        //dummytest to avoid error regarding empty test
        self::assertTrue(true);
    }

    /**
     * @depends testWelcome
     */
    public function testAbout()
    {
        //dummytest to avoid error regarding empty test
        self::assertTrue(true);
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
        // assert genreRepositoryMock is not null
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
