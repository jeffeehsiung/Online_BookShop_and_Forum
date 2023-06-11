<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Book;
use App\Entity\LikedBook;
use App\Entity\DislikedBook;
use App\Entity\FollowedBook;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class BookTest extends TestCase
{
    /**
     * @group include
     */
    public function testConstruct(): void
    {
        $book = new Book();

        $this->assertInstanceOf(ArrayCollection::class, $book->getLikedBooks());
        $this->assertInstanceOf(ArrayCollection::class, $book->getDislikedBooks());
        $this->assertInstanceOf(ArrayCollection::class, $book->getFollowedBooks());
    }

    /**
     * @group include
     */
    public function testGetSetTitle(): void
    {
        $book = new Book();
        $title = 'Example Title';

        $book->setTitle($title);

        $this->assertSame($title, $book->getTitle());
    }

    /**
     * @group include
     */
    public function testAddLikedBook(): void
    {
        $book = new Book();
        $likedBook = new LikedBook();

        $book->addLikedBook($likedBook);

        $this->assertTrue($book->getLikedBooks()->contains($likedBook));
        $this->assertSame($book, $likedBook->getBook());
    }
    /**
     * @group include
     */
    public function testRemoveLikedBook(): void
    {
        $book = new Book();
        $likedBook = new LikedBook();

        $book->addLikedBook($likedBook);
        $book->removeLikedBook($likedBook);

        $this->assertFalse($book->getLikedBooks()->contains($likedBook));
        $this->assertNull($likedBook->getBook());
    }

    /**
     * @group include
     */
    public function testAddDislikedBook(): void
    {
        $book = new Book();
        $dislikedBook = new DislikedBook();

        $book->addDislikedBook($dislikedBook);

        $this->assertTrue($book->getDislikedBooks()->contains($dislikedBook));
        $this->assertSame($book, $dislikedBook->getBook());
    }

    /**
     * @group include
     */
    public function testRemoveDislikedBook(): void
    {
        $book = new Book();
        $dislikedBook = new DislikedBook();

        $book->addDislikedBook($dislikedBook);
        $book->removeDislikedBook($dislikedBook);

        $this->assertFalse($book->getDislikedBooks()->contains($dislikedBook));
        $this->assertNull($dislikedBook->getBook());
    }

    /**
     * @group include
     */
    public function testAddFollowedBook(): void
    {
        $book = new Book();
        $user = new User();
        $followedBook = new FollowedBook($user, $book);

        $book->addFollowedBook($followedBook);

        $this->assertTrue($book->getFollowedBooks()->contains($followedBook));
        $this->assertSame($book, $followedBook->getBook());
    }

    /**
     * @group include
     */
    public function testRemoveFollowedBook(): void
    {
        $book = new Book();
        $user = new User();
        $followedBook = new FollowedBook($user, $book);

        $book->addFollowedBook($followedBook);
        $book->removeFollowedBook($followedBook);

        $this->assertFalse($book->getFollowedBooks()->contains($followedBook));
        $this->assertNull($followedBook->getBook());
    }
}
