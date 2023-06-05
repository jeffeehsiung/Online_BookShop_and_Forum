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
        $user = $manager->getRepository(User::class)->findOneBy(['email' => 'hometest@test.com']);
        //add the first 100 uneven books to the database for a specific user
        for ($i = 0; $i < 10; $i++) {
            $book = $manager->getRepository(Book::class)->findOneBy(['id' => 1*($i+1)+rand(0,9)]);
            $followedBook = new FollowedBook($user, $book);
            $manager->persist($followedBook);
        }
        $manager->flush();
    }
    public function getOrder(){
        return 6;
    }
}