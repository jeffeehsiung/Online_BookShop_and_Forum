<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Book;
use App\Entity\FollowedBook;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class FollowedBookTest extends TestCase
{
    /**
     * @group include
     */
    public function testConstruct(): void
    {
        $user = new User();
        $book = new Book();
        $followedBook = new FollowedBook($user, $book);

        $this->assertInstanceOf(Book::class, $followedBook->getBook());
        $this->assertInstanceOf(User::class, $followedBook->getUser());
    }

    /**
     * @group include
     */
    public function testGetSetUser(): void
    {
        $followedBook = new FollowedBook(null, null);
        $user = new User();

        $followedBook->setUser($user);

        $this->assertEquals($user, $followedBook->getUser());
    }

    /**
     * @group include
     */
    public function testGetSetBook(): void
    {
        $followedBook = new FollowedBook(null, null);
        $book = new Book();

        $followedBook->setBook($book);

        $this->assertEquals($book, $followedBook->getBook());
    }
}
