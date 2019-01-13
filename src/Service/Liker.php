<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 10.01.19.
 * Time: 14:11
 */

namespace App\Service;

use App\Entity\LikeCounter;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\LikeCounterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class Liker
{
    private $repository;
    private $entityManager;
    private $security;

    /**
     * Liker service constructor
     * 
     * @param LikeCounterRepository $likeCounterRepository
     * @param Security $security
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(LikeCounterRepository $likeCounterRepository, Security $security, EntityManagerInterface $entityManager)
    {
        $this->repository = $likeCounterRepository;
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    /**
     * Function for liking post
     *
     * @param Post $post
     */
    public function likePost(Post $post):void
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $postLike = $this->repository->findOneBy([
            'post' => $post,
            'owner' => $user,
        ]);

        if ($postLike instanceof LikeCounter) {
            $postLike->giveOneLike();
        } else {
            $postLike = new LikeCounter();
            $postLike->setPost($post);
            $postLike->setOwner($user);
            $postLike->setValue();
        }

        $this->entityManager->persist($postLike);
        $this->entityManager->flush();
    }

}