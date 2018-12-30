<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 29.12.18.
 * Time: 19:31
 */

namespace App\Controller;

use App\Entity\LikeCounter;
use App\Entity\Post;
use App\Repository\LikeCounterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeCounterController extends AbstractController
{
    private $repository;

    /**
     * LikeCounterController constructor.
     * @param LikeCounterRepository $likeCounterRepository
     */
    public function __construct(LikeCounterRepository $likeCounterRepository)
    {
        $this->repository = $likeCounterRepository;
    }

    /**
     * Ajax call for like post
     *
     * @Route("/ajax-like", name="ajax_like")
     * @param Request $request
     * @return \Knp\Component\Pager\Pagination\PaginationInterface|string|Response
     */
    public function ajaxLike(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $post = $this->getDoctrine()->getRepository(Post::class)->find($request->get('post'));
            $this->likePost($post);
            $totalLikes = $this->repository->findTotalLikesForPost($post);

            return $this->json(['likes' => $totalLikes[1],
                'likes2' => $totalLikes]);
        }

        throw $this->createNotFoundException('Not found');
    }


    /**
     * Helper function for liking post
     *
     * @param Post $post
     */
    public function likePost(Post $post):void
    {
        $postLike = $this->repository->findOneBy([
            'post' => $post,
            'owner' => $this->getUser(),
        ]);

        if ($postLike instanceof LikeCounter) {
            $postLike->setValue(1);

        } else {
            $postLike = new LikeCounter();
            $postLike->setPost($post);
            $postLike->setOwner($this->getUser());
            $postLike->setValue(1);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($postLike);
        $entityManager->flush();
    }


}