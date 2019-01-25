<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 10.01.19.
 * Time: 13:36
 */

namespace App\Service;

use App\Entity\Favorite;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\FavoriteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Component\Security\Core\Security;


class Favoriter
{
    private $repository;
    private $entityManager;
    private $security;

    /**
     * Favoriter service constructor
     *
     * @param FavoriteRepository $favoriteRepository
     * @param EntityManagerInterface $entityManager
     * @param Security $security
     */
    public function __construct(FavoriteRepository $favoriteRepository, EntityManagerInterface $entityManager, Security $security)
    {
        $this->repository = $favoriteRepository;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    /**
     * Function for making post favorite or removing
     *
     * @param Post $post
     * @return array
     */
    public function favorite(Post $post)
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $postFavorite = $this->repository->findOneBy([
            'post' => $post,
            'user' => $user,
        ]);

        if ($postFavorite) {
            $favorite =  $this->removeFavorite($postFavorite);
        } else {
           $favorite = $this->makeFavorite($post);
        }

        return $favorite;
    }

    /**
     * @param Favorite $favorite
     * @return array
     */
    public function removeFavorite(Favorite $favorite)
    {
        if ($favorite instanceof Favorite) {
            try {
                $this->entityManager->remove($favorite);
                $this->entityManager->flush();
            } catch (ORMException $e) {
                $favoriteJSON = ['error' => $e];
            }

            return $favoriteJSON = ['favorite' => false];
        }
    }

    /**
     * @param Post $post
     * @return array
     */
    public function makeFavorite(Post $post)
    {
        $favorite = new Favorite();
        /** @var User $user */
        $user = $this->security->getUser();
        $favorite->setUser($user);
        $favorite->setPost($post);

        $this->entityManager->persist($favorite);
        $this->entityManager->flush();
        $favoriteJSON = ['favorite' => true];

        return $favoriteJSON;
    }
}