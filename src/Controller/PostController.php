<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 19.12.18.
 * Time: 16:40
 */

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController
 * @package App\Controller
 */
class PostController extends AbstractController
{
    private $paginator;

    /**
     * PostController constructor.
     * @param PaginatorInterface $paginator
     */
    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * @Route("/", name="homepage")
     * @param PostRepository $repository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(PostRepository $repository):Response
    {
        $posts = $repository->findAll();
        $pagination = $this->paginator->paginate($posts);

        return $this->render('post/index.html.twig', [
                'posts' => $pagination,
            ]
        );
    }

    /**
     * @Route("/posts/new", name="post_new")
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

            return $this->redirectToRoute('homepage');
        }

        return $this->render('post/index.html.twig', array(
            'form' => $form->createView()
        ));
    }



    public function delete(Post $post)
    {

    }


    public function show(Post $post)
    {

    }

    public function edit(Post $post)
    {

    }

}