<?php

namespace App\DataFixtures;

use App\Entity\Avatar;
use App\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use League\Csv\Reader;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class GenreFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $csvFile = 'src/csv_files/genres.csv';

        $data = Reader::createFromPath($csvFile, 'r');
        $data->setDelimiter(';');

        $data->setHeaderOffset(0);

        foreach ($data as $row){
            $genre = new Genre();
            $genre->setGenre($row['genre']);
            $manager->persist($genre);
        }
        $manager->flush();
    }
    public function getOrder():int
    {
        return 1; //smaller means sooner
    }
}