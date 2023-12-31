<?php

namespace App\DataFixtures;

use App\Entity\Avatar;
use App\Entity\Genre;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use League\Csv\Reader;

class UserFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $csvFile = 'src/csv_files/users.csv';

        $data = Reader::createFromPath($csvFile, 'r');
        $data->setDelimiter(';');
        $data->setHeaderOffset(0);

        foreach ($data as $row){
            $user = new User();
            $user->setFirstName($row['first_name']);
            $user->setLastName($row['last_name']);
            $user->setUsername($row['username']);
            $user->setPassword($row['password']);
            $user->setEmail($row['email']);
            $avatar = $manager->getRepository(Avatar::class)->findOneBy(['id' => $row['avatar_id']]);
            $user->setAvatar($avatar);
            $user->setBio($row['bio']);
            $rolesArray = str_getcsv($row['roles']);
            $user->setRoles($rolesArray);
            $user->setIsVerified($row['is_verified']);

            $manager->persist($user);
      }
        $manager->flush();
    }

    public function getOrder():int
    {
        return 2; //smaller means sooner
    }


}
