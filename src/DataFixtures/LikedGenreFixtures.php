<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Genre;
use App\Entity\LikedBook;
use App\Entity\LikedGenre;
use App\Entity\User;
use App\Repository\BookRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LikedGenreFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        //set user with userID = 1006 == Jens
        $user = $manager->getRepository(User::class)->findOneBy(['email' => 'hometest@test.com']);
        //add the first 100 books in the database to the likedbooks for a specific user
        for ($i = 0; $i < 4; $i++) {
            $genre = $manager->getRepository(Genre::class)->findOneBy(['id' => $i+1]);
            $likedGenre = new LikedGenre();
            $likedGenre->setGenre($genre);
            $likedGenre->setUser($user);
            $manager->persist($likedGenre);
        }
        $manager->flush();
    }
    public function getOrder():int
    {
        return 6;
    }
}