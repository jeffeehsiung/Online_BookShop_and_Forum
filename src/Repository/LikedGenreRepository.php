<?php

namespace App\Repository;

use App\Entity\LikedGenre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LikedGenre>
 *
 * @method LikedGenre|null find($id, $lockMode = null, $lockVersion = null)
 * @method LikedGenre|null findOneBy(array $criteria, array $orderBy = null)
 * @method LikedGenre[]    findAll()
 * @method LikedGenre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikedGenreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LikedGenre::class);
    }

    public function save(LikedGenre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(LikedGenre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
