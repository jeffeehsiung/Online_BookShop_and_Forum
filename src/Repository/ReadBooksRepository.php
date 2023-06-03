<?php

namespace App\Repository;

use App\Entity\ReadBooks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReadBooks>
 *
 * @method ReadBooks|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReadBooks|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReadBooks[]    findAll()
 * @method ReadBooks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReadBooksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReadBooks::class);
    }

    public function save(ReadBooks $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ReadBooks $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
