<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TagController extends AbstractController
{
    private $repository;

    /**
     * Tag constructor.
     * @param TagRepository $repository
     */
    public function __construct(TagRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * List of all tags
     *
     * @param TagRepository $tagRepository
     * @return Response
     */
    public function index(TagRepository $tagRepository): Response
    {
        return $this->render('admin/tag/index.html.twig', array(
            'tags' => $tagRepository->findAll()
            )
        );
    }

    /**
     * Create new tag
     *
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tag);
            $entityManager->flush();

            return $this->redirectToRoute('admin_tag_index');
        }

        return $this->render('admin/tag/new.html.twig', array(
            'tag' => $tag,
            'form' => $form->createView(),
            )
        );
    }

    /**
     * Show one tag
     *
     * @param Tag $tag
     * @return Response
     */
    public function show(Tag $tag): Response
    {
        return $this->render('admin/tag/show.html.twig', array(
            'tag' => $tag,
            )
        );
    }

    /**
     * Edit one tag
     *
     * @param Request $request
     * @param Tag $tag
     * @return Response
     */
    public function edit(Request $request, Tag $tag): Response
    {
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_tag_index', array(
                'id' => $tag->getId()
                )
            );
        }

        return $this->render('admin/tag/edit.html.twig', array(
            'tag' => $tag,
            'form' => $form->createView(),
            )
        );
    }

    /**
     * Delete one tag
     *
     * @param Tag $tag
     * @return Response
     */
    public function delete(Tag $tag): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($tag);
        $entityManager->flush();

        return $this->redirectToRoute('admin_tag_index');
    }
}
