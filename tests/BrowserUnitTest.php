<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Controller\BookableController;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\GenreRepository;
use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class BrowserUnitTest extends TestCase
{
    public function testBrowsing(): void
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