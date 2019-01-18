<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 17.01.19.
 * Time: 16:25
 */

namespace App\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends AbstractController
{
    /**
     * Admin homepage
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('admin/homepage.html.twig');
    }

}