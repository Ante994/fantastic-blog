<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 19.12.18.
 * Time: 16:40
 */

namespace App\Controller;

use App\Entity\Favorite;
use App\Entity\LikeCounter;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PostController
 * @package App\Controller
 */
class PostController extends AbstractController
{
    private $paginator;
    private $repository;

    /**
     * PostController constructor.
     * @param PaginatorInterface $paginator
     * @param PostRepository $postRepository
     */
    public function __construct(PaginatorInterface $paginator, PostRepository $postRepository)
    {
        $this->paginator = $paginator;
        $this->repository  = $postRepository;
    }

    /**
     * Displaying posts title
     *
     * @param Request $request
     * @return \Knp\Component\Pager\Pagination\PaginationInterface|Response
     */
    public function index(Request $request)
    {
        $pagination = $this->paginate($request);

        return $this->render(
            'post/index.html.twig',
            array('pagination' => $pagination)
        );
    }

    /**
     * Show one post with all details
     *
     * @param Post $post
     * @ParamConverter("post", options={"mapping": {"post": "slug"}}))
     * @return Response
     */
    public function show(Post $post)
    {
        $post = $this->repository->find($post);
        $totalLikes = $this->getDoctrine()->getRepository(LikeCounter::class)->findTotalLikesForPost($post);
        $favorite = $this->getDoctrine()->getRepository(Favorite::class)->findBy(['post'=> $post, 'user'=> $this->getUser()]);

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'totalLikes' => $totalLikes[1],
            'favorite' => $favorite,
            'form' => $this->createForm(CommentType::class)->createView(),
            ]
        );
    }
    /**
     * Ajax call for paginate posts
     *
     * @param Request $request
     * @return \Knp\Component\Pager\Pagination\PaginationInterface|Response
     */
    public function ajaxIndex(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $pagination = $this->paginate($request);

            return $this->render('post/index_paginate.html.twig', [
                    'pagination' => $pagination,
                ]
            );
        }

        throw $this->createNotFoundException('Not found');
    }

    /**
     * Helper function for paginate posts
     *
     * @param Request $request
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function paginate(Request $request): \Knp\Component\Pager\Pagination\PaginationInterface
    {
        $search = $request->query->get('q');
        if ($search) {
            $posts = $this->repository->search($search);
        } else {
            $posts = $this->repository->findAll();
        }

        $pagination = $this->paginator->paginate(
            $posts,
            $request->query->getInt('page', 1),
            5
        );

        return $pagination;
    }
}