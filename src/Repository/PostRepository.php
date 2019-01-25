<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * Search all post which contain passed title in post translations for user locale
     *
     * @param $title
     * @param string $locale
     * @return Post[]
     */
    public function search($title, $locale='en')
    {
        return $this->createQueryBuilder('p')
            ->join('p.postTranslation', 'pt')
            ->where('pt.title_'.$locale.' LIKE :title')
            ->setParameter('title', '%'.$title.'%')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Find all post ordered by date (default DESC)
     *
     * @param string $orderBy
     * @return Post[]
     */
    public function findAllOrderByDate($orderBy='DESC')
    {
        return $this->findBy(array(), array('dateCreated' => $orderBy));
    }
}
