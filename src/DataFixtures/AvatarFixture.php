<?php

namespace App\DataFixtures;

use App\Entity\Avatar;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use League\Csv\Reader;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class AvatarFixture extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $csvFile = 'src/csv_files/avatars.csv';

        $data = Reader::createFromPath($csvFile, 'r');
        $data->setDelimiter(';');

        $data->setHeaderOffset(0);

        foreach ($data as $row) {
            $avatar = new Avatar();
            $avatar->setUrl($row['url']);
            $manager->persist($avatar);
        }
        $manager->flush();
    }
    public function getOrder(): int
    {
        return 1; // smaller means sooner
    }
}