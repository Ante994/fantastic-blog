<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 12.12.18.
 * Time: 22:44
 */

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{

    public function index()
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        return $this->render('default/index.html.twig', [
            'users' => $users,
        ]);
    }

}