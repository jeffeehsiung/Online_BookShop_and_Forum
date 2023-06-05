<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Avatar;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class AvatarTest extends TestCase
{
    /**
     * @group include
     */
    public function testConstruct(): void
    {
        $avatar = new Avatar();
        $users = $avatar->getUsers();

        $this->assertInstanceOf(ArrayCollection::class, $users);
    }

    /**
     * @group include
     */
    public function testGetSetUrl(): void
    {
        $avatar = new Avatar();
        $url = 'https://example.com/avatar.jpg';

        $avatar->setUrl($url);

        $this->assertEquals($url, $avatar->getUrl());
    }

    /**
     * @group include
     */
    public function testGetUsers(): void
    {
        $avatar = new Avatar();
        $user1 = new User();
        $user2 = new User();

        $avatar->addUser($user1);
        $avatar->addUser($user2);

        $users = $avatar->getUsers();

        $this->assertCount(2, $users);
        $this->assertTrue($users->contains($user1));
        $this->assertTrue($users->contains($user2));
    }

    /**
     * @group include
     */
    public function testAddUser(): void
    {
        $avatar = new Avatar();
        $user = new User();

        $avatar->addUser($user);

        $this->assertTrue($avatar->getUsers()->contains($user));
        $this->assertSame($avatar, $user->getAvatar());
    }

    /**
     * @group include
     */
    public function testRemoveUser(): void
    {
        $avatar = new Avatar();
        $user = new User();

        $avatar->addUser($user);
        $avatar->removeUser($user);

        $this->assertFalse($avatar->getUsers()->contains($user));
        $this->assertNull($user->getAvatar());
    }
}
