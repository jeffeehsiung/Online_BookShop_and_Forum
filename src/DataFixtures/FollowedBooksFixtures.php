<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\FollowedBook;
use App\Entity\LikedBook;
use App\Entity\User;
use App\Repository\BookRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class FollowedBooksFixtures extends Fixture implements OrderedFixtureInterface

{
    public function load(ObjectManager $manager)
    {
        $user = $manager->getRepository(User::class)->findOneBy(['email' => 'profiletest@test.com']);
        //add the first 5 uneven books to the database for a specific user
        for ($i = 1; $i < 8; $i++) {
           // $book = $manager->getRepository(Book::class)->findOneBy(['id' => 1*($i+1)+rand(0,9)]);
            if($i%2!=0)
            {
                $book = $manager->getRepository(Book::class)->findOneBy(['id' => $i]);
                $followedBook = new FollowedBook($user, $book);
                $manager->persist($followedBook);
            }

        }
        //
        $manager->flush();
    }
    public function getOrder():int
    {
        return 6;
    }
}
//"The Hunger Games (The Hunger Games, #1)","Twilight (Twilight, #1)","The Great Gatsby","The Hobbit"
