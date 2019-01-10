<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 10.01.19.
 * Time: 12:05
 */

namespace App\Service;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class Paginator
{

    private $paginator;
    private $limit;

    /**
     * PostController constructor.
     * @param PaginatorInterface $paginator
     */
    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
        $this->limit = 5;
    }

    /**
     * Helper function for paginate objects
     *
     * @param $objects
     * @param Request $request
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function paginate($objects, Request $request): \Knp\Component\Pager\Pagination\PaginationInterface
    {
        $page = $request->query->getInt('page', 1);

        $pagination = $this->paginator->paginate($objects, $page, $this->limit);

        return $pagination;
    }


}