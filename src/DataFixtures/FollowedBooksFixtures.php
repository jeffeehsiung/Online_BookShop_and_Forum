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
        //set user with userID = 1006 == Jens
        $user = $manager->getRepository(User::class)->findOneBy(['id' => 1006]);
        //add the first 100 uneven books to the database for a specific user
        for ($i = 0; $i < 100; $i++) {
            $book = $manager->getRepository(Book::class)->findOneBy(['id' => 2*$i+1]);
            $followedBook = new FollowedBook($user, $book);
            $manager->persist($followedBook);
        }
        $manager->flush();
    }
    public function getOrder(){
        return 6;
    }
}