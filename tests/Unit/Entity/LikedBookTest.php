<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Book;
use App\Entity\LikedBook;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class LikedBookTest extends TestCase
{
    public function testGetSetUser(): void
    {
        $followedBook = new LikedBook(null, null);
        $user = new User();

        $followedBook->setUser($user);

        $this->assertEquals($user, $followedBook->getUser());
    }

    public function testGetSetBook(): void
    {
        $followedBook = new LikedBook(null, null);
        $book = new Book();

        $followedBook->setBook($book);

        $this->assertEquals($book, $followedBook->getBook());
    }
}
