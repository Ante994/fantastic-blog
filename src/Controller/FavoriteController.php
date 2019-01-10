<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 29.12.18.
 * Time: 19:32
 */

namespace App\Controller;

use App\Entity\Post;
use App\Service\Favoriter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @IsGranted("ROLE_USER")
 * Class FavoriteController
 * @package App\Controller
 */
class FavoriteController extends AbstractController
{
    private $favoriter;

    /**
     * FavoriteController constructor.
     * @param Favoriter $favoritePost
     */
    public function __construct(Favoriter $favoritePost)
    {
        $this->favoriter = $favoritePost;
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
            $favorite = $this->favoriter->favorite($post);

            return $this->json($favorite, 200);
        }

        throw $this->createNotFoundException('Not found');
    }
}