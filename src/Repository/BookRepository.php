<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
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
    public function findAllByTitle($book_title, $booksPerPage): array
    {
        $queryBuilder = $this->createQueryBuilder('books')
            ->orderBy('books.title', 'DESC');
        if($book_title){
            $queryBuilder->andWhere('books.title LIKE :val')
                ->setParameter('val', '%'.$book_title.'%');
        }
        return $queryBuilder->setMaxResults($booksPerPage)->getQuery()->getResult();
//        return $this->createQueryBuilder('books')
//            ->andWhere('books.title LIKE :val')
//            ->setParameter('val', '%'.$book_title.'%')
//            ->orderBy('books.title', 'ASC')
//            ->setMaxResults($booksPerPage)
//            ->getQuery()
//            ->getResult()
//        ;
    }

    /**
     * @param $genre_ids is an array of genre ids
     * iterate through the $genre_ids and add a where clause for each genre_id
     * find all books for each genre_id
     * @return Book[] Returns an array of Book object
     */
    public function findAllByGenreIds($gen_ids): array
    {
        $qb = $this->createQueryBuilder('books');
        $qb->where($qb->expr()->in('books.genre_id', $gen_ids));
        $qb->orderBy('books.genre_id', 'ASC');
        $qb->orderBy('books.title', 'ASC');
        $qb->setMaxResults(30);
        return $qb->getQuery()->getResult();
    }

    // findall by genre and title
    public function findAllByGenreAndTitle($gen_ids, $title): array
    {
        $qb = $this->createQueryBuilder('books');
        $qb->where($qb->expr()->in('books.genre_id', $gen_ids));
        $qb->andWhere('books.title LIKE :val')
            ->setParameter('val', '%'.$title.'%');
        $qb->orderBy('books.genre_id', 'ASC');
        $qb->orderBy('books.title', 'ASC');
        $qb->setMaxResults(30);
        return $qb->getQuery()->getResult();
    }

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
