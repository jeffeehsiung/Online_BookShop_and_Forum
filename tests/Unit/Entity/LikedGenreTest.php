<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Genre;
use App\Entity\LikedGenre;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class LikedGenreTest extends TestCase
{
    public function testGetSetGenre(): void
    {
        $likedGenre = new LikedGenre();
        $genre = new Genre();

        $likedGenre->setGenre($genre);

        $this->assertEquals($genre, $likedGenre->getGenre());
    }

    public function testGetSetUser(): void
    {
        $likedGenre = new LikedGenre();
        $user = new User();

        $likedGenre->setUser($user);

        $this->assertEquals($user, $likedGenre->getUser());
    }
}
