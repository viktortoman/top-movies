<?php

namespace App\Repository;

use App\Entity\TMDB;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TMDB|null find($id, $lockMode = null, $lockVersion = null)
 * @method TMDB|null findOneBy(array $criteria, array $orderBy = null)
 * @method TMDB[]    findAll()
 * @method TMDB[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TMDBRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TMDB::class);
    }

    // /**
    //  * @return TMDB[] Returns an array of TMDB objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TMDB
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
