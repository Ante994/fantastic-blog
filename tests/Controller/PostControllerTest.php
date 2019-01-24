<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 03.01.19.
 * Time: 11:54
 */

namespace App\Tests\Controller;

use App\Tests\FixturesTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class PostControllerTest extends FixturesTestCase
{

    /** @var Client */
    protected $client;

    public function setUp()
    {
        parent::setUp();
        $this->client = $this->loginUser();
    }

    public function testShowPostIndexPage()
    {
        $this->client->request('GET', "/");

        $this->assertEquals(200,  $this->client->getResponse()->getStatusCode());
    }

    public function testIndexPageContainsTitleTranslation()
    {
        $crawler = $this->client->request('GET', "/");

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Fantastic blog")')->count()
        );
    }

    public function testIndexPageContainsTitleHrvTranslation()
    {
        $crawler = $this->client->request('GET', "/hr/");

        $this->assertEquals(200,  $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Fantastične novosti")')->count()
        );
    }

    public function testPostDetailsPageWorks()
    {
        $this->client->request('GET', "/posts/test-1");
        $this->assertEquals(200,  $this->client->getResponse()->getStatusCode());

        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }


    public function testUserCanLikePost()
    {
        $container = self::$kernel->getContainer();
        $em = $container->get('doctrine')->getManager();
        $postRepository = $em->getRepository('App:Post');

        $this->client->request('GET', "/posts/test-1");
        $this->assertEquals(200,  $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $post = $postRepository->find(1);
        $this->assertEquals(200,  $this->client->getResponse()->getStatusCode());
        $this->client->xmlHttpRequest('POST', '/ajax-like', array('post' => $post));

        $this->assertEquals(200,  $this->client->getResponse()->getStatusCode());
    }

    public function testUserCanFavoritePost()
    {
        $container = self::$kernel->getContainer();
        $em = $container->get('doctrine')->getManager();
        $postRepository = $em->getRepository('App:Post');

        $this->client->request('GET', "/posts/test-1");
        $this->assertEquals(200,  $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $post = $postRepository->find(1);
        $this->assertEquals(200,  $this->client->getResponse()->getStatusCode());
        $this->client->xmlHttpRequest('POST', '/ajax-favorite', array('post' => $post));

        $this->assertEquals(200,  $this->client->getResponse()->getStatusCode());
    }

    public function testUserCanCommentPost()
    {
        $this->client->request('GET', "/posts/test-1");
        $this->assertEquals(200,  $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $container = self::$kernel->getContainer();
        $em = $container->get('doctrine')->getManager();
        $postRepo = $em->getRepository('App:Post');
        $post = $postRepo->find(1);
        $this->client->xmlHttpRequest('POST', '/ajax-comment', array('post' => $post, 'content' => 'test comment'));
        $this->assertEquals(200,  $this->client->getResponse()->getStatusCode());
    }

    public function testUserCanDeleteCommentPost()
    {
        $this->client->request('GET', "/posts/test-1");
        $this->assertEquals(200,  $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $container = self::$kernel->getContainer();
        $em = $container->get('doctrine')->getManager();
        $commentRepo = $em->getRepository('App:Comment');

        $postRepo = $em->getRepository('App:Post');
        $post = $postRepo->find(1);

        $this->client->xmlHttpRequest('POST', '/ajax-comment', array('post' => $post, 'content' => 'test comment for delete'));
        $this->assertEquals(200,  $this->client->getResponse()->getStatusCode());

        $comment = $commentRepo->findOneBy(['content' => 'test comment for delete', 'author' => 2]);
        $this->client->xmlHttpRequest('DELETE', '/ajax-delete', array('comment' => $comment));
        $this->assertEquals(200,  $this->client->getResponse()->getStatusCode());
    }

    private function loginUser()
    {
        return static::createClient(array(), array(
            'PHP_AUTH_USER' => 'ante@fb.com',
            'PHP_AUTH_PW'   => '1234',
            'HTTP_HOST' => 'fantastic-blog.puphpet'
        ));
    }
}