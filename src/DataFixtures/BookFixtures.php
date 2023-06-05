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
        $this->addToDatabase($csvFile, $manager);
        $csvFile = 'src/csv_files/books_pt2.csv';
        $this->addToDatabase($csvFile, $manager);
        $csvFile = 'src/csv_files/books_pt3.csv';
        $this->addToDatabase($csvFile, $manager);
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
    public function addToDatabase(string $csvFile, ObjectManager $manager): void
    {
        $data = Reader::createFromPath($csvFile, 'r');
        $data->setDelimiter(';');
        $data->setHeaderOffset(0);

        foreach ($data as $row) {
            $book = new Book();
            $book->setTitle($row['title']);
            $book->setGoodreadsBookId($row['goodreads_book_id']);
            $book->setBestBookId($row['best_book_id']);
            $book->setWorkId($row['work_id']);
            $book->setBooksCount($row['books_count']);
            $book->setOriginalPublicationYear($row['original_publication_year']);
            $author = $manager->getRepository(Author::class)->findOneBy(['id' => $row['author_id']]);
            $book->setAuthor($author);
            $book->setIsbn($row['isbn']);
            $book->setIsbn13($row['isbn13']);
            $book->setLanguageCode($row['language_code']);
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
}