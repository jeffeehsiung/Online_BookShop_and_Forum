<?php

namespace App\Repository;

use App\Entity\DislikedBook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DislikedBooks>
 *
 * @method DislikedBook|null find($id, $lockMode = null, $lockVersion = null)
 * @method DislikedBook|null findOneBy(array $criteria, array $orderBy = null)
 * @method DislikedBook[]    findAll()
 * @method DislikedBook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DislikedBookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DislikedBook::class);
    }

    public function save(DislikedBook $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DislikedBook $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
