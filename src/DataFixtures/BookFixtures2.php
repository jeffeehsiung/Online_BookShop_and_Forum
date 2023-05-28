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

class BookFixtures2 extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $csvFile = 'src/csv_files/books_pt2.csv';

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
    public function getOrder(){
        return 4;
    }

}