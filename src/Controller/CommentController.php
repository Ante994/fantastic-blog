<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 28.12.18.
 * Time: 17:20
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Repository\CommentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @IsGranted("ROLE_USER")
 * Class CommentController
 * @package App\Controller
 */
class CommentController extends AbstractController
{
    private $repository;

    /**
     * FavoriteController constructor.
     * @param CommentRepository $commentRepository
     */
    public function __construct(CommentRepository $commentRepository)
    {
        $this->repository  = $commentRepository;
    }

    /**
     * Ajax call for creating comment on post
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function ajaxComment(Request $request)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($request->get('post'));
        $content = $request->get('content');

        if ($request->isXmlHttpRequest() && $post instanceof Post && $content) {
            $comment = $this->new($post, $content);

            return $this->json([
                'comment' => $comment->getContent(),
                'author' => $comment->getAuthor()->getDisplayName(),
                'created' => $comment->getCreated()->format('j.m.Y G:i'),
            ], 200);
        }

        throw $this->createNotFoundException('Not found');
    }

    /**
     * Ajax call for deleting comment on post
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function ajaxDelete(Request $request)
    {
        $comment = $this->repository->find($request->get('comment'));

        if ($request->isXmlHttpRequest() && $comment instanceof Comment) {
            $this->delete($comment);

            return $this->json(['deleted' => true], 200);
        }

        throw $this->createNotFoundException('Not found');
    }

    /**
     * Creating new comment on request object from ajax call
     *
     * @param Post $post
     * @param string $content
     * @return Comment
     * @throws \Exception
     */
    public function new(Post $post, string $content): Comment
    {
        $comment = new Comment();
        $comment->setContent($content);
        $comment->setAuthor($this->getUser());
        $comment->setCreated(new \DateTime());
        $comment->setPost($post);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($comment);
        $entityManager->flush();

        return $comment;
    }

    /**
     * Deleting comment
     *
     * @param Comment $comment
     * @throws \Exception
     */
    private function delete(Comment $comment): void
    {
        if ($comment->getAuthor() === $this->getUser() || $this->isGranted('ROLE_ADMIN')) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }
    }

}