<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 10.01.19.
 * Time: 18:18
 */

namespace App\Tests\Service;


use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class CommenterTest extends TestCase
{

    public function testItBuildsAndPersistsEnclosure()
    {
        $em = $this->createMock(EntityManagerInterface::class);






    }

}