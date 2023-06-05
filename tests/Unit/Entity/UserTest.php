<?php

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use App\Entity\Avatar;
use App\Entity\FollowedBook;
use App\Entity\LikedGenre;
use App\Entity\LikedBook;
use App\Entity\DislikedBook;

class UserTest extends TestCase
{
    /**
     * @group include
     */
    public function testConstruct(): void
    {
        $user = new User();

        $this->assertInstanceOf(User::class, $user);
        $this->assertEmpty($user->getFollowedBooks());
        $this->assertEmpty($user->getLikedGenres());
        $this->assertEmpty($user->getLikedBooks());
        $this->assertEmpty($user->getDislikedBooks());
    }

    /**
     * @group include
     */
    public function testGetSetFirstName(): void
    {
        $user = new User();
        $firstName = 'John';

        $user->setFirstName($firstName);

        $this->assertEquals($firstName, $user->getFirstName());
    }

    /**
     * @group include
     */
    public function testGetSetAvatar(): void
    {
        $user = new User();
        $avatar = new Avatar();

        $user->setAvatar($avatar);

        $this->assertEquals($avatar, $user->getAvatar());
    }

    /**
     * @group include
     */
    public function testGetSetFollowedBooks(): void
    {
        $user = new User();
        $followedBook = new FollowedBook(null, null);

        $user->addFollowedBook($followedBook);

        $this->assertCount(1, $user->getFollowedBooks());
        $this->assertTrue($user->getFollowedBooks()->contains($followedBook));

        $user->removeFollowedBook($followedBook);

        $this->assertCount(0, $user->getFollowedBooks());
        $this->assertFalse($user->getFollowedBooks()->contains($followedBook));
    }

    /**
     * @group include
     */
    public function testGetSetLikedGenres(): void
    {
        $user = new User();
        $likedGenre = new LikedGenre();

        $user->addLikedGenre($likedGenre);

        $this->assertCount(1, $user->getLikedGenres());
        $this->assertTrue($user->getLikedGenres()->contains($likedGenre));

        $user->removeLikedGenre($likedGenre);

        $this->assertCount(0, $user->getLikedGenres());
        $this->assertFalse($user->getLikedGenres()->contains($likedGenre));
    }

    /**
     * @group include
     */
    public function testGetSetLikedBooks(): void
    {
        $user = new User();
        $likedBook = new LikedBook();

        $user->addLikedBook($likedBook);

        $this->assertCount(1, $user->getLikedBooks());
        $this->assertTrue($user->getLikedBooks()->contains($likedBook));

        $user->removeLikedBook($likedBook);

        $this->assertCount(0, $user->getLikedBooks());
        $this->assertFalse($user->getLikedBooks()->contains($likedBook));
    }

    /**
     * @group include
     */
    public function testGetSetDislikedBooks(): void
    {
        $user = new User();
        $dislikedBook = new DislikedBook();

        $user->addDislikedBook($dislikedBook);

        $this->assertCount(1, $user->getDislikedBooks());
        $this->assertTrue($user->getDislikedBooks()->contains($dislikedBook));

        $user->removeDislikedBook($dislikedBook);

        $this->assertCount(0, $user->getDislikedBooks());
        $this->assertFalse($user->getDislikedBooks()->contains($dislikedBook));
    }
}
