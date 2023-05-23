<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\LikedBook;
use App\Entity\User;
use App\Repository\BookRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DislikedBooksFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //set id equal to the one you actually want to use
        $user = $manager->getRepository(User::class)->findOneBy(['id' => 1006]);
        //add the first 100 books in the database to the likedbooks for a specific user
        for ($i = 0; $i < 100; $i++) {
            //make sure that liked and disliked don't overlap!!!!
            $book = $manager->getRepository(Book::class)->findOneBy(['id' => $i+101]);
            $likedBook = new LikedBook();
            $likedBook->setBook($book);
            $likedBook->setUser($user);
            $manager->persist($likedBook);
        }
        $manager->flush();
    }
}