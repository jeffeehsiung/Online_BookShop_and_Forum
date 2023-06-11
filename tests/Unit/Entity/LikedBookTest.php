<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Book;
use App\Entity\LikedBook;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class LikedBookTest extends TestCase
{
    /**
     * @group include
     */
    public function testGetSetUser(): void
    {
        $followedBook = new LikedBook();
        $user = new User();

        $followedBook->setUser($user);

        $this->assertEquals($user, $followedBook->getUser());
    }

    /**
     * @group include
     */
    public function testGetSetBook(): void
    {
        $followedBook = new LikedBook();
        $book = new Book();

        $followedBook->setBook($book);

        $this->assertEquals($book, $followedBook->getBook());
    }
}
