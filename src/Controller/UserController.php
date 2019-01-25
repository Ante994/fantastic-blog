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
use App\Entity\LikeCounter;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @IsGranted("ROLE_USER")
 * Class UserController
 * @package App\Controller
 */
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
     * Show user profile
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(): Response
    {
        $user = $this->repository->find($this->getUser());
        $favorite = $this->getDoctrine()->getRepository(Favorite::class)->findFavoritePostForUser($this->getUser());
        $comments = $this->getDoctrine()->getRepository(Comment::class)->findLatestUserComments($this->getUser());
        $likes = $this->getDoctrine()->getRepository(LikeCounter::class)->findUserLikesOnPosts($this->getUser());

        return $this->render('user/show.html.twig', [
                'user' => $user,
                'favorites' => $favorite,
                'comments' => $comments,
                'likes' => $likes
            ]
        );
    }

    /**
     * User can edit yourself profile
     *
     * @param Request $request
     * @param User $user
     * @ParamConverter("user", options={"mapping": {"user": "id"}}))
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, User $user): Response
    {
        $user = $this->repository->find($user);

        if ($this->getUser() !== $user) {
            throw $this->createNotFoundException("This does not exist or you not allowed be here!");
        }

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