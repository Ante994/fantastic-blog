<?php

namespace App\Repository;

use App\Entity\PostDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PostDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostDetail[]    findAll()
 * @method PostDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostDetailRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PostDetail::class);
    }

    // /**
    //  * @return PostDetail[] Returns an array of PostDetail objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PostDetail
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
