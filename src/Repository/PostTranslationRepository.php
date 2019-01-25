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

    /**
     * @param string $slug
     * @param string $locale
     * @return PostTranslation|[]
     */
    public function findBySlug($slug, $locale='en')
    {
        $qb = $this->createQueryBuilder('pt')
            ->andWhere('pt.slug_'.$locale.' = :slug_'.$locale);

        try {
            return $qb
                ->setParameter('slug_' . $locale, $slug)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {

        }
    }

    /**
     * @param string $title
     * @param string $locale
     * @return PostTranslation|[]
     */
    public function findByTitleAndLocale($title, $locale='en')
    {
        $qb = $this->createQueryBuilder('pt')
            ->andWhere('pt.title_'.$locale.' LIKE :title');

        return $qb
            ->setParameter('title', '%'.$title.'%')
            ->getQuery()
            ->execute();
    }
}
