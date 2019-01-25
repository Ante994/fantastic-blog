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
use App\Entity\PostTranslation;
use App\Form\CommentType;
use App\Repository\PostRepository;
use App\Service\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;
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
     * @param Paginator $paginator
     * @param PostRepository $postRepository
     */
    public function __construct(Paginator $paginator, PostRepository $postRepository)
    {
        $this->paginator = $paginator;
        $this->repository = $postRepository;
    }

    /**
     * Displaying posts title
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $posts = $this->filter($request);
        $pagination = $this->paginator->paginate($posts, $request);

        if ($request->isXmlHttpRequest()) {
            return $this->ajaxIndex($pagination);
        }

        return $this->render(
            'post/index.html.twig',
            array('pagination' => $pagination)
        );
    }

    /**
     * Show one post with all details
     *
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function show(Request $request)
    {
        $locale = $request->getLocale();
        $slug = $request->get('post');

        /** @var PostTranslation $postTranslation */
        $postTranslation = $this->getDoctrine()->getRepository(PostTranslation::class)->findBySlug($slug, $locale);
        $post = $postTranslation->getPost();
        $totalLikes = $this->getDoctrine()->getRepository(LikeCounter::class)->findTotalLikesForPost($post);
        $favorite = $this->getDoctrine()->getRepository(Favorite::class)->findBy(['post' => $post, 'user' => $this->getUser()]);

        return $this->render('post/show.html.twig', [
                'post' => $post,
                'totalLikes' => $totalLikes[1],
                'favorite' => $favorite,
                'form' => $this->createForm(CommentType::class)->createView(),
            ]
        );
    }

    /**
     * Ajax method for paginate posts
     *
     * @param PaginationInterface $pagination
     * @return Response
     */
    private function ajaxIndex(PaginationInterface $pagination)
    {
        return $this->render('post/index_paginate.html.twig', [
                'pagination' => $pagination,
            ]
        );
    }

    /**
     * Filter function for post if is passed keyword else found all
     *
     * @param Request $request
     * @return Post[]
     */
    private function filter(Request $request)
    {
        $param = $request->query->get('q');
        $locale = $request->getLocale();

        if ($param) {
            $objects = $this->repository->search($param, $locale);
        } else {
            $objects = $this->repository->findAllOrderByDate();
        }

        return $objects;
    }
}