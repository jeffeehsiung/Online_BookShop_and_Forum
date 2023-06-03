<?php

namespace App\Repository;

use App\Entity\LikedBook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LikedBook>
 *
 * @method LikedBook|null find($id, $lockMode = null, $lockVersion = null)
 * @method LikedBook|null findOneBy(array $criteria, array $orderBy = null)
 * @method LikedBook[]    findAll()
 * @method LikedBook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikedBookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LikedBook::class);
    }

    public function save(LikedBook $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(LikedBook $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
