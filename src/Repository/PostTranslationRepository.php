<?php

namespace App\Repository;

use App\Entity\PostTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PostTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostTranslation[]    findAll()
 * @method PostTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostTranslationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PostTranslation::class);
    }

    // /**
    //  * @return PostTranslation[] Returns an array of PostTranslation objects
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
    public function findOneBySomeField($value): ?PostTranslation
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findBySlug($slug, $locale='en')
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.slug_'.$locale.' = :slug_'.$locale);

        try {
            return $qb
                ->setParameter('slug_' . $locale, $slug)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {

        }
    }
}
