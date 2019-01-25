<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 12.01.19.
 * Time: 13:57
 */

namespace App\Tests;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Abstract class that extend base WebTestCase class for
 * functional testing with data fixtures
 *
 * Class FixturesTestCase
 * @package App\Tests
 */
abstract class FixturesTestCase extends WebTestCase
{
    protected $client;

    protected function setUp()
    {
        $this->client = static::createClient();
        $em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        if (!isset($metadatas)) {
            $metadatas = $em->getMetadataFactory()->getAllMetadata();
        }

        /** @var EntityManager $em */
        $schemaTool = new SchemaTool($em);
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