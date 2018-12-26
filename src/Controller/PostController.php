<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 19.12.18.
 * Time: 16:40
 */

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\PostType;
use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/", name="post_index")
     * @param Request $request
     * @return \Knp\Component\Pager\Pagination\PaginationInterface|Response
     */
    public function index(Request $request)
    {
        $pagination = $this->searchPost($request);

        return $this->render('post/index.html.twig', [
                'pagination' => $pagination,
            ]
        );
    }

    /**
     * Creating new post
     *
     * @Route("/posts/new", name="post_new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function new(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())  {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('post_index');
        }

        return $this->render('post/new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Delete one post
     *
     * @Route("/posts/{post}/delete", name="post_delete")
     * @param Post $post
     * @return Response
     */
    public function delete(Post $post)
    {
        if (!$this->getUser() instanceof User ) {
            throw $this->createNotFoundException("This does not exist or you not allowed be here!");
        }

        $post = $this->repository->find($post);
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute('post_index');
    }

    /**
     * Displaying one post
     *
     * @Route("/posts/{post}", name="post_show")
     * @param Post $post
     * @return Response
     */
    public function show(Post $post)
    {
        if (!$this->getUser() instanceof User ) {
            throw $this->createNotFoundException("This does not exist or you not allowed be here!");
        }

        $post = $this->repository->find($post);

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'form' => $this->createForm(CommentType::class)->createView()
            ]
        );
    }

    /**
     * Editing post
     *
     * @Route("/posts/{post}/edit", name="post_edit")
     * @param Request $request
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit(Request $request, Post $post)
    {
        if (!$this->getUser() instanceof User ) {
            throw $this->createNotFoundException("This does not exist or you not allowed be here!");
        }

        $post = $this->repository->find($post);

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('post_index');
        }

        return $this->render('post/new.html.twig',[
                'form'=> $form->createView(),
            ]
        );
    }

    /**
     * Ajax call for paginate posts
     *
     * @Route("/ajax-post", name="ajax_index")
     * @param Request $request
     * @return \Knp\Component\Pager\Pagination\PaginationInterface|Response
     */
    public function ajaxIndex(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $pagination = $this->searchPost($request);

            return $this->render('post/index_paginate.html.twig', [
                    'pagination' => $pagination,
                ]
            );
        }
    }

    /**
     * @param Request $request
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function searchPost(Request $request): \Knp\Component\Pager\Pagination\PaginationInterface
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