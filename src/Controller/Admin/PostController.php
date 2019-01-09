<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 09.01.19.
 * Time: 14:16
 */

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Repository\PostRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PostController
 * @package App\Controller\Admin
 */
class PostController extends AbstractController
{
    private $repository;

    /**
     * PostController constructor
     *
     * @param PostRepository $postRepository
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->repository  = $postRepository;
    }

    /**
     * Creating new post
     *
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
     * @param Post $post
     * @ParamConverter("post", options={"mapping": {"post": "slug"}}))
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
     * Editing post
     *
     * @param Request $request
     * @param Post $post
     * @ParamConverter("post", options={"mapping": {"post": "slug"}}))
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit(Request $request, Post $post)
    {
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
}