<?php

namespace App\Tests\Unit\Entity;

use App\Entity\DislikedBook;
use PHPUnit\Framework\TestCase;
use App\Entity\User;
use App\Entity\Book;

class DislikedBookTest extends TestCase
{
    /**
     * @group include
     */
    public function testGetSetUser(): void
    {
        $dislikedBook = new DislikedBook();
        $user = new User();

        $dislikedBook->setUser($user);

        $this->assertEquals($user, $dislikedBook->getUser());
    }

    /**
     * @group include
     */
    public function testGetSetBook(): void
    {
        $dislikedBook = new DislikedBook();
        $book = new Book();

        $dislikedBook->setBook($book);

        $this->assertEquals($book, $dislikedBook->getBook());
    }
}
