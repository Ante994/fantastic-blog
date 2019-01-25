<?php

namespace App\Repository;

use App\Entity\Favorite;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Favorite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Favorite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Favorite[]    findAll()
 * @method Favorite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavoriteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Favorite::class);
    }

    /**
     * Find user favorites
     *
     * @param User $user
     * @return Favorite[]
     */
    public function findFavoritePostForUser(User $user)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.user = :user')
            ->setParameter('user', $user)
            ->join('f.post', 'fav_post')
            ->select('f')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Find users all favorite posts
     *
     * @param User $user
     * @return Post[]
     */
    public function findFavoritePostsForUser(User $user)
    {
        $dql = 'SELECT p.* FROM App:Favorite f INNER JOIN App:Post p ON f.post_id = p.id where f.user_id = :id';

         return $this->getEntityManager()
             ->createQuery($dql)
             ->setParameter('id', $user->getId())
             ->getResult();
    }
}
