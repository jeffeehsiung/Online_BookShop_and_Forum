<?php

namespace App\Repository;

use App\Entity\FollowedBook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FollowedBook>
 *
 * @method FollowedBook|null find($id, $lockMode = null, $lockVersion = null)
 * @method FollowedBook|null findOneBy(array $criteria, array $orderBy = null)
 * @method FollowedBook[]    findAll()
 * @method FollowedBook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FollowedBookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FollowedBook::class);
    }

    public function save(FollowedBook $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FollowedBook $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
