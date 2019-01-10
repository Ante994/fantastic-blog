<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 29.12.18.
 * Time: 19:31
 */

namespace App\Controller;

use App\Entity\Post;
use App\Repository\LikeCounterRepository;
use App\Service\Liker;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @IsGranted("ROLE_USER")
 * Class LikeCounterController
 * @package App\Controller
 */
class LikeCounterController extends AbstractController
{
    private $liker;
    private $repository;
    /**
     * LikeCounterController constructor.
     * @param LikeCounterRepository $repository
     * @param Liker $liker
     */
    public function __construct(LikeCounterRepository $repository, Liker $liker)
    {
        $this->repository = $repository;
        $this->liker = $liker;
    }

    /**
     * Ajax call for like post and return total likes for post
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function ajaxLike(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $post = $this->getDoctrine()->getRepository(Post::class)->find($request->get('post'));
            $this->liker->likePost($post);
            $totalLikes = $this->repository->findTotalLikesForPost($post);

            return $this->json([
                'likes' => $totalLikes[1],
            ]);
        }

        throw $this->createNotFoundException('Not found');
    }

}