<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\LikedBook;
use App\Entity\User;
use App\Repository\BookRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LikedBooksFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        //set user with userID = 1006 == Jens
        $user = $manager->getRepository(User::class)->findOneBy(['id' => 1006]);
        //add the first 100 books in the database to the likedbooks for a specific user
        for ($i = 0; $i < 100; $i++) {
            $book = $manager->getRepository(Book::class)->findOneBy(['id' => $i+1]);
            $likedBook = new LikedBook();
            $likedBook->setBook($book);
            $likedBook->setUser($user);
            $manager->persist($likedBook);
        }
        $manager->flush();
    }
    public function getOrder():int
    {
        return 6;
    }
}
