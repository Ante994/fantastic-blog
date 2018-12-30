<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 24.12.18.
 * Time: 17:12
 */

namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Favorite;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/profile", name="user_show")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(): Response
    {
        $user = $this->repository->find($this->getUser());
        $favoritePosts = $this->getDoctrine()->getRepository(Favorite::class)->findFavoritePostForUser($this->getUser());
        $comments = $this->getDoctrine()->getRepository(Comment::class)->findLatestUserComments($this->getUser());

        return $this->render('user/show.html.twig', [
                'user' => $user,
                'favorites' => $favoritePosts,
                'comments' => $comments,
            ]
        );
    }

    /**
     * @Route("/profile/{user}", name="user_edit")
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, User $user): Response
    {
        $user = $this->repository->find($user);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_show');
        }

        return $this->render(
            'user/edit.html.twig',
            array('form' => $form->createView())
        );
    }
}