<?php

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use League\Csv\Reader;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class AuthorsFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $csvFile = 'src/csv_files/authors.csv';

        $data = Reader::createFromPath($csvFile, 'r');
        $data->setDelimiter(';');

        $data->setHeaderOffset(0);

        foreach ($data as $row) {
            $id = $row['id'];
            $author_name = $row['author_name'];

            $author = new Author();
            $author->setId($id);

            $author->setAuthorName($author_name);

            $this->addReference('author' . $id, $author);

            $manager->persist($author);
        }
        $manager->flush();
    }
    public function getOrder():int
    {
        return 1; //smaller means sooner
    }

}