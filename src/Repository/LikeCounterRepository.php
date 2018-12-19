<?php

namespace App\Repository;

use App\Entity\LikeCounter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LikeCounter|null find($id, $lockMode = null, $lockVersion = null)
 * @method LikeCounter|null findOneBy(array $criteria, array $orderBy = null)
 * @method LikeCounter[]    findAll()
 * @method LikeCounter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikeCounterRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LikeCounter::class);
    }

    // /**
    //  * @return LikeCounter[] Returns an array of LikeCounter objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LikeCounter
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
