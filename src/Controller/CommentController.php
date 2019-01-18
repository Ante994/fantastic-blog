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
use App\Service\Commenter;
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
    private $commenter;

    /**
     * FavoriteController constructor.
     * Initialized comment repository, commenter service
     * for making and deleting comments
     * @param CommentRepository $commentRepository
     * @param Commenter $commenter
     */
    public function __construct(CommentRepository $commentRepository, Commenter $commenter)
    {
        $this->repository  = $commentRepository;
        $this->commenter = $commenter;
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
            $comment = $this->commenter->new($post, $content);

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
            $this->commenter->delete($comment);

            return $this->json(['deleted' => true], 200);
        }

        throw $this->createNotFoundException('Not found');
    }
}