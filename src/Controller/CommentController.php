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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * Ajax call for creating comment on post
     *
     * @Route("/ajax-comment", name="ajax_comment")
     * @param Request $request
     * @return \Knp\Component\Pager\Pagination\PaginationInterface|string|Response
     * @throws \Exception
     */
    public function ajaxCreateComment(Request $request)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($request->get('post'));
        $content = $request->get('content');

        if ($request->isXmlHttpRequest() && $post instanceof Post && $content) {
            $comment = $this->createNewComment($post, $content);

            return $this->json([
                'comment' => $comment->getContent(),
                'author' => $comment->getAuthor()->getDisplayName(),
                'created' => $comment->getCreated()->format('j.m.Y G:i'),
            ]);
        }

        throw $this->createNotFoundException('Not found');
    }

    /**
     * Creating new comment on request object from ajax call
     * @param Post $post
     * @param string $content
     * @return Comment
     * @throws \Exception
     */
    public function createNewComment(Post $post, string $content): Comment
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

}