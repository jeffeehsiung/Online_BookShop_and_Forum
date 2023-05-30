<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Genre;
use App\Entity\Book;
use App\Entity\LikedGenre;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class GenreTest extends TestCase
{
    public function testConstruct()
    {
        $genre = new Genre();

        $this->assertInstanceOf(Genre::class, $genre);
        $this->assertNull($genre->getId());
        $this->assertNull($genre->getGenre());
        $this->assertInstanceOf(ArrayCollection::class, $genre->getBooks());
        $this->assertInstanceOf(ArrayCollection::class, $genre->getLikedGenres());
        $this->assertCount(0, $genre->getBooks());
        $this->assertCount(0, $genre->getLikedGenres());
    }

    public function testGetSetGenre()
    {
        $genre = new Genre();
        $genreName = 'Fantasy';

        $genre->setGenre($genreName);

        $this->assertEquals($genreName, $genre->getGenre());
    }

    public function testBooksRelationship()
    {
        $genre = new Genre();
        $book = new Book();

        $genre->addBook($book);

        $this->assertCount(1, $genre->getBooks());
        $this->assertSame($genre, $book->getGenre());

        $genre->removeBook($book);

        $this->assertCount(0, $genre->getBooks());
        $this->assertNull($book->getGenre());
    }

    public function testLikedGenresRelationship()
    {
        $genre = new Genre();
        $likedGenre = new LikedGenre();

        $genre->addLikedGenre($likedGenre);

        $this->assertCount(1, $genre->getLikedGenres());
        $this->assertSame($genre, $likedGenre->getGenre());

        $genre->removeLikedGenre($likedGenre);

        $this->assertCount(0, $genre->getLikedGenres());
        $this->assertNull($likedGenre->getGenre());
    }
}
