<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 12.12.18.
 * Time: 22:44
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class DefaultController
{

    public function index()
    {
        try {
            $number = random_int(0, 100);
        } catch (\Exception $e) {
        }

        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }

}