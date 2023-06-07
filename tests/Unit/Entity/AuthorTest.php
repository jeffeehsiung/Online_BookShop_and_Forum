<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Author;
use App\Entity\Book;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class AuthorTest extends TestCase
{
    /**
     * @group include
     */
    public function testConstruct(): void
    {
        $author = new Author();

        $this->assertInstanceOf(Author::class, $author);
        $this->assertNull($author->getId());
        $this->assertNull($author->getAuthorName());
        $this->assertInstanceOf(ArrayCollection::class, $author->getBooks());
        $this->assertCount(0, $author->getBooks());
    }

    /**
     * @group include
     */
    public function testGetSetAuthorName(): void
    {
        $author = new Author();
        $authorName = 'John Doe';

        $author->setAuthorName($authorName);

        $this->assertEquals($authorName, $author->getAuthorName());
    }

    /**
     * @group include
     */
    public function testGetBooks(): void
    {
        $author = new Author();
        $book1 = new Book();
        $book2 = new Book();

        $author->addBook($book1);
        $author->addBook($book2);

        $books = $author->getBooks();

        $this->assertCount(2, $books);
        $this->assertTrue($books->contains($book1));
        $this->assertTrue($books->contains($book2));
    }

    /**
     * @group include
     */
    public function testAddBook(): void
    {
        $author = new Author();
        $book = new Book();

        $author->addBook($book);

        $this->assertTrue($author->getBooks()->contains($book));
        $this->assertSame($author, $book->getAuthor());
    }

    /**
     * @group include
     */
    public function testRemoveBook(): void
    {
        $author = new Author();
        $book = new Book();

        $author->addBook($book);
        $author->removeBook($book);

        $this->assertFalse($author->getBooks()->contains($book));
        $this->assertNull($book->getAuthor());
    }
}
