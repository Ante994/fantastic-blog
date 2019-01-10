<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 10.01.19.
 * Time: 14:53
 */

namespace App\Service;


use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class Commenter
{
    private $repository;
    private $entityManager;
    private $security;

    /**
     * FavoriteController constructor.
     * @param CommentRepository $commentRepository
     * @param EntityManagerInterface $entityManager
     * @param Security $security
     */
    public function __construct(CommentRepository $commentRepository, EntityManagerInterface $entityManager, Security $security)
    {
        $this->repository = $commentRepository;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    /**
     * Creating new comment
     *
     * @param Post $post
     * @param string $content
     * @return Comment
     * @throws \Exception
     */
    public function new(Post $post, string $content): Comment
    {
        /** @var User $user */
        $user = $this->security->getUser();
        $comment = new Comment();
        $comment->setContent($content);
        $comment->setAuthor($user);
        $comment->setCreated(new \DateTime());
        $comment->setPost($post);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        return $comment;
    }

    /**
     * Deleting comment
     *
     * @param Comment $comment
     * @throws \Exception
     */
    public function delete(Comment $comment): void
    {
        /** @var User $user */
        $user = $this->security->getUser();
        if ($comment->getAuthor() === $user || 'ROLE_ADMIN' == $user->getRoles()[0]) {
            $this->entityManager->remove($comment);
            $this->entityManager->flush();
        }
    }

}