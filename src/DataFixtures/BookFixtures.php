<?php
namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Avatar;
use App\Entity\Book;
use App\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use League\Csv\Reader;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class BookFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        //done in parts because it is too much data to handle at once
        //even now the memory limit needs to be increased to 256 MB in PHP.ini
        $csvFile = 'src/csv_files/books.csv';
        $data = Reader::createFromPath($csvFile, 'r');
        $data->setDelimiter(';');
        $data->setHeaderOffset(0);

        foreach ($data as $row) {
            $book = new Book();
            $book->setTitle($row['title']);
            $book->setOriginalPublicationYear($row['original_publication_year']);
            $author = $manager->getRepository(Author::class)->findOneBy(['id' => $row['author_id']]);
            $book->setAuthor($author);
            $book->setLikes($row['likes']);
            $book->setImageUrl($row['image_url']);
            $book->setSmallImageUrl($row['small_image_url']);
            $genre = $manager->getRepository(Genre::class)->findOneBy(['id' => $row['genre_id']]);
            $book->setGenre($genre);
            $book->setDislikes($row['dislikes']);
            $manager->persist($book);
        }
        $manager->flush();
    }
    public function getOrder():int
    {
        return 3; //smaller means earlier
    }

    /**
     * @param string $csvFile
     * @param ObjectManager $manager
     * @return void
     * @throws \League\Csv\Exception
     * @throws \League\Csv\InvalidArgument
     * @throws \League\Csv\UnavailableStream
     */
}