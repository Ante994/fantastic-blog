<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 12.01.19.
 * Time: 13:57
 */

namespace App\Tests;

use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Liip\FunctionalTestBundle\Test\WebTestCase;


abstract class FixturesTestCase extends WebTestCase
{
    protected $client;
    protected $em;

    protected function setUp()
    {
        $this->client = static::createClient();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        if (!isset($metadatas)) {
            $metadatas = $this->em->getMetadataFactory()->getAllMetadata();
        }
        $schemaTool = new SchemaTool($this->em);
        $schemaTool->dropDatabase();
        if (!empty($metadatas)) {
            try {
                $schemaTool->createSchema($metadatas);
            } catch (ToolsException $e) {
            }
        }
        $this->postFixtureSetup();

        $this->loadFixtures(array(
            'App\DataFixtures\AppFixtures',
        ));

    }
}