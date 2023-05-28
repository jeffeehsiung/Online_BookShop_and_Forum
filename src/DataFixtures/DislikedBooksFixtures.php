<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\DislikedBook;
use App\Entity\LikedBook;
use App\Entity\User;
use App\Repository\BookRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class DislikedBooksFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        //set id equal to the one you actually want to use
        $user = $manager->getRepository(User::class)->findOneBy(['id' => 1006]);
        //add the first 100 books in the database to the likedbooks for a specific user
        for ($i = 0; $i < 100; $i++) {
            //make sure that liked and disliked don't overlap!!!!
            $book = $manager->getRepository(Book::class)->findOneBy(['id' => $i+101]);
            $dislikedBooks = new DislikedBook();
            $dislikedBooks->setBook($book);
            $dislikedBooks->setUser($user);
            $manager->persist($dislikedBooks);
        }
        $manager->flush();
    }
    public function getOrder(){
        return 6;
    }
}