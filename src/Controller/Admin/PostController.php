<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 09.01.19.
 * Time: 14:16
 */

namespace App\Controller\Admin;

use App\Entity\Favorite;
use App\Entity\LikeCounter;
use App\Entity\Post;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Service\Paginator;
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
     * Displaying posts title for Admin with operation for CRUD
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $pagination = $this->paginator->paginate($this->repository->findAll(), $request);

        return $this->render(
            'admin/post/index.html.twig',
            array('pagination' => $pagination)
        );
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

            return $this->redirectToRoute('admin_post_index');
        }

        return $this->render('admin/post/new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Show one post with all details
     *
     * @param Post $post
     * @ParamConverter("post", options={"mapping": {"post": "id"}}))
     * @return Response
     */
    public function show(Post $post)
    {
        $post = $this->repository->find($post);
        $totalLikes = $this->getDoctrine()->getRepository(LikeCounter::class)->findTotalLikesForPost($post);
        $favorite = $this->getDoctrine()->getRepository(Favorite::class)->findBy(['post' => $post, 'user' => $this->getUser()]);

        return $this->render('admin/post/show.html.twig', array(
                'post' => $post,
                'totalLikes' => $totalLikes[1],
                'favorite' => $favorite,
                'form' => $this->createForm(CommentType::class)->createView(),
            )
        );
    }

    /**
     * Delete one post
     *
     * @param Post $post
     * @ParamConverter("post", options={"mapping": {"post": "id"}}))
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

        return $this->redirectToRoute('admin_post_index');
    }

    /**
     * Editing post
     *
     * @param Request $request
     * @param Post $post
     * @ParamConverter("post", options={"mapping": {"post": "id"}}))
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

            return $this->redirectToRoute('admin_post_index');
        }

        return $this->render('admin/post/edit.html.twig',array(
                'form'=> $form->createView(),
            )
        );
    }
}