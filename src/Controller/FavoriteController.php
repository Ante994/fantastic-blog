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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/ajax-favorite", name="ajax_favorite")
     * @param Request $request
     * @return \Knp\Component\Pager\Pagination\PaginationInterface|string|Response
     */
    public function ajaxFavorite(Request $request)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($post = $request->get('post'));
        if ($request->isXmlHttpRequest() && $post instanceof Post) {
            return $this->favoritePost($post);
        }

        throw $this->createNotFoundException('Not found');
    }

    /**
     * Helper function for favorite post
     *
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function favoritePost(Post $post): \Symfony\Component\HttpFoundation\JsonResponse
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

        return $this->json($favorite);
    }

}