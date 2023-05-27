<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Controller\BookableController;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\GenreRepository;
use App\Repository\BookRepository;

class BrowserUnitTest extends TestCase
{
    public function testBrowsing(): void
    {
        // Mock the dependencies
        $genreRepositoryMock = $this->createMock(GenreRepository::class);
        $bookRepositoryMock = $this->createMock(BookRepository::class);
        $requestMock = $this->createMock(Request::class);

        // Set up the expectations
        $genreRepositoryMock->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $bookRepositoryMock->expects($this->once())
            ->method('findAllByTitle')
            ->with('Book Title', 0)
            ->willReturn([]);

        // Create an instance of the controller
        $controller = new BookableController();

        // Call the browsing method
        $response = $controller->browsing($genreRepositoryMock, $bookRepositoryMock, $requestMock, $requestMock, $requestMock, 'book-title');

        // Assert the response
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
