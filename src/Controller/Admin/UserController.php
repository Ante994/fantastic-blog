<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 17.01.19.
 * Time: 16:02
 */

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Entity\Favorite;
use App\Entity\LikeCounter;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    private $repository;

    /**
     * UserController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->repository  = $userRepository;
    }

    /**
     * Admin can see list of all users
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(): Response
    {
        $users = $this->repository->findAll();

        return $this->render('admin/user/index.html.twig', array(
            'users' => $users,
            )
        );
    }

    /**
     * Admin can access user profile
     *
     * @param User $user
     * @ParamConverter("user", options={"mapping": {"user": "id"}}))
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(User $user): Response
    {
        $favorite = $this->getDoctrine()->getRepository(Favorite::class)->findFavoritePostForUser($user);
        $comments = $this->getDoctrine()->getRepository(Comment::class)->findLatestUserComments($user);
        $likes = $this->getDoctrine()->getRepository(LikeCounter::class)->findUserLikesOnPosts($user);

        return $this->render('admin/user/show.html.twig', [
                'user' => $user,
                'favorites' => $favorite,
                'comments' => $comments,
                'likes' => $likes
            ]
        );

    }

    /**
     * Admin can edit user profile
     *
     * @param Request $request
     * @param User $user
     * @ParamConverter("user", options={"mapping": {"user": "id"}}))
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, User $user): Response
    {
        $user = $this->repository->find($user);

        if (!$user instanceof User ) {
            throw $this->createNotFoundException("User don't exist!");
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('admin_user_show', array(
                'user' => $user->getId()
            ));
        }

        return $this->render(
            'admin/user/edit.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * Admin can delete user
     *
     * @param User $user
     * @ParamConverter("user", options={"mapping": {"user": "id"}}))
     * @return Response
     */
    public function delete(User $user)
    {
        dump($user);
        if (!$user instanceof User ) {
            throw $this->createNotFoundException("User don't exist!");
        }

        $user = $this->repository->find($user);
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('admin_user_index');
    }


}