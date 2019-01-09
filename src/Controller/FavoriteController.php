<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 29.12.18.
 * Time: 19:32
 */

namespace App\Controller;

use App\Entity\Favorite;
use App\Entity\Post;
use App\Repository\FavoriteRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @IsGranted("ROLE_USER")
 * Class FavoriteController
 * @package App\Controller
 */
class FavoriteController extends AbstractController
{
    private $repository;

    /**
     * FavoriteController constructor.
     * @param FavoriteRepository $favoriteRepository
     */
    public function __construct(FavoriteRepository $favoriteRepository)
    {
        $this->repository  = $favoriteRepository;
    }

    /**
     * Ajax call for favorite post
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function ajaxFavorite(Request $request)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($post = $request->get('post'));
        if ($request->isXmlHttpRequest() && $post instanceof Post) {
            $favorite = $this->favoritePost($post);
            return $this->json($favorite, 200);
        }

        throw $this->createNotFoundException('Not found');
    }

    /**
     * Function for making post favorite or removing
     *
     * @param Post $post
     * @return array
     */
    public function favoritePost(Post $post)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $postFavorite = $this->repository->findOneBy([
            'post' => $post,
            'user' => $this->getUser(),
        ]);

        if (!$postFavorite instanceof Favorite) {
            $postFavorite = new Favorite();
            $postFavorite->setUser($this->getUser());
            $postFavorite->setPost($post);
            $favorite = ['favorite' => true];
            $entityManager->persist($postFavorite);
        } else {
            $favorite = ['favorite' => false];
            $entityManager->remove($postFavorite);

        }
        $entityManager->flush();

        return $favorite;
    }

}