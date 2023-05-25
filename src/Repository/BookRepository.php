<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    // create paginator for book list
    public const PAGINATOR_PER_PAGE = 34;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function save(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public  function findAll():array
    {
        // override the findAll() method to automatically order by id (newest first).
        // Ordering the book list by pk may facilitate browsing: ref: https://symfony.com/doc/6.2/the-fast-track/en/12-event.html
        return $this->findBy([],['id'=>'DESC']);
    }

    public function findPopular(){
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT book 
            FROM App\Entity\Book book 
            ORDER BY book.likes DESC
            '
        );
        return $query->getResult();
    }


    /**
     * @return Book[] Returns an array of Book objects and sort by genre_id and title
     */
    public function findAllByGenre($gen_id): array
    {
        return $this->createQueryBuilder('genbooks')
            ->andWhere('genbooks.exampleField = :val')
            ->setParameter('val', $gen_id)
                ->orderBy('genbooks.genre_id', 'ASC')
                ->orderBy('genbooks.title', 'ASC')
                ->setMaxResults(30)
                ->getQuery()
                ->getResult()
        ;
    }

    /**
     * find all book objects by title
     * @return Book[] Returns an array of Book objects
     */
    public function findAllByTitleOld($book_title, $booksPerPage): array
    {
        $queryBuilder = $this->createQueryBuilder('books')
            ->orderBy('books.id', 'DESC');
        if($book_title){
            $queryBuilder->andWhere('books.title LIKE :val')
                ->setParameter('val', '%'.$book_title.'%');
        }
        return $queryBuilder->setMaxResults($booksPerPage)->getQuery()->getResult();
    }

    public function findAllByTitle($book_title, int $offset): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('books')
            ->orderBy('books.id', 'DESC');
        if($book_title){
            $queryBuilder->andWhere('books.title LIKE :val')
                ->setParameter('val', '%'.$book_title.'%');
        }
        $queryBuilder->setMaxResults(self::PAGINATOR_PER_PAGE)->setFirstResult($offset)
            ->getQuery();
//            ->getResult();
        return new Paginator($queryBuilder);
    }


    public function filterByGenre(array $genreIDs): array
    {
        // create a query builder
        $queryBuilder = $this->createQueryBuilder('books')
            ->orderBy('books.id', 'DESC');
        // if there are genre ids, filter by genre ids
        if ($genreIDs) {
            // use orWhere() instead of andWhere() to filter by multiple genres
            foreach ($genreIDs as $key => $genreID) {
                $queryBuilder->orWhere('books.genre_id = :genre_id'.$key)
                    ->setParameter('genre_id'.$key, $genreID);
            }
//            $queryBuilder->andWhere('books.genre_id IN (:genreIDs)')
//                ->setParameter('genreIDs', $genreIDs);
        }
        // execute the query and return the result
        return $queryBuilder->setMaxResults(self::PAGINATOR_PER_PAGE)->getQuery()->getResult();
    }

    /**
     * @param $genre_ids is an array of genre ids
     * iterate through the $genre_ids and add a where clause for each genre_id
     * find all books for each genre_id
     * @return Book[] Returns an array of Book object
     */


//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
