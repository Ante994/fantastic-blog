<?php

namespace App\Repository;

use App\Entity\LikeCounter;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
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

    /**
     * @param Post $post
     * @return LikeCounter
     * @throws NonUniqueResultException
     */
    public function findTotalLikesForPost(Post $post)
    {
        $query = $this->createQueryBuilder('l')
            ->select('sum(l.value)')
            ->groupby('l.post')
            ->having('l.post =:postId')
            ->setParameter('postId', $post)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * @param User $user
     * @return LikeCounter[]
     */
    public function findUserLikesOnPosts(User $user)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.owner = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;
    }
}
