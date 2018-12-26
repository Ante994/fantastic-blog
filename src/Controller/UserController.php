<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 24.12.18.
 * Time: 17:12
 */

namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

        return $this->render('user/show.html.twig', [
                'user' => $user,
            ]
        );
    }

    /**
     * @Route("/profile/{user}", name="user_edit")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(User $user): Response
    {
        $user = $this->repository->find($user);

        return $this->render('user/edit.html.twig', [
                'user' => $user,
            ]
        );
    }
}