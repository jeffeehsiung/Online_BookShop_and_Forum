<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Genre;
use App\Entity\Library;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use League\Csv\Reader;


class LibrariesFixture extends Fixture implements OrderedFixtureInterface

{
    public function load(ObjectManager $manager)
    {

        $csvFile = 'src/csv_files/libraries.csv';

        $data = Reader::createFromPath($csvFile, 'r');
        $data->setDelimiter(';');

        $data->setHeaderOffset(0);

        foreach ($data as $row) {
            $library = new Library();
            $library->setCity($row['city']);
            $library->setCountry($row['country']);
            $library->setEmail($row['email']);
            $manager->persist($library);
        }
        $manager->flush();
    }
    public function getOrder()
    {
        return 1; //smaller means sooner
    }
}