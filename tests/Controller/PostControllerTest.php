<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 03.01.19.
 * Time: 11:54
 */

namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{

    private function loginUser()
    {
        return static::createClient(array(), array(
            'PHP_AUTH_USER' => 'ante@fb.com',
            'PHP_AUTH_PW'   => '1234',
            'HTTP_HOST' => 'fantastic-blog.puphpet'
        ));
    }

    public function testShowPostIndexPage()
    {
        $client = static::createClient();
        $client->request('GET', "/");

        $this->assertEquals(200,  $client->getResponse()->getStatusCode());
    }

    public function testIndexPageContainsTitleTranslation()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', "/");

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Fantastic blog")')->count()
        );
    }

    public function testIndexPageContainsTitleHrvTranslation()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', "/hr/");

        $this->assertEquals(200,  $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Fantastične novosti")')->count()
        );
    }

    public function testPostDetailsPageWorks()
    {
        $client = self::createClient();
        $client->request('GET', "/posts/test-1");
        $this->assertEquals(200,  $client->getResponse()->getStatusCode());

        $this->assertTrue($client->getResponse()->isSuccessful());
    }


    public function testUserCanLikePost()
    {
        $client = $this->loginUser();
        $client->request('GET', "/posts/test-1");
        $this->assertEquals(200,  $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isSuccessful());

        $container = self::$kernel->getContainer();
        $em = $container->get('doctrine')->getManager();
        $postRepo = $em->getRepository('App:Post');
        $post = $postRepo->findOneBy(['slug' => 'test-1']);
        $this->assertEquals(200,  $client->getResponse()->getStatusCode());
        $client->xmlHttpRequest('POST', '/ajax-like', array('post' => $post));

        $this->assertEquals(200,  $client->getResponse()->getStatusCode());
    }

    public function testUserCanFavoritePost()
    {
        $client = $this->loginUser();
        $client->request('GET', "/posts/test-1");
        $this->assertEquals(200,  $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isSuccessful());

        $container = self::$kernel->getContainer();
        $em = $container->get('doctrine')->getManager();
        $postRepo = $em->getRepository('App:Post');
        $post = $postRepo->findOneBy(['slug' => 'test-1']);
        $this->assertEquals(200,  $client->getResponse()->getStatusCode());
        $client->xmlHttpRequest('POST', '/ajax-favorite', array('post' => $post));

        $this->assertEquals(200,  $client->getResponse()->getStatusCode());


    }

    public function testUserCanCommentPost()
    {
        $client = $this->loginUser();
        $client->request('GET', "/posts/test-1");
        $this->assertEquals(200,  $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isSuccessful());

        $container = self::$kernel->getContainer();
        $em = $container->get('doctrine')->getManager();
        $postRepo = $em->getRepository('App:Post');
        $post = $postRepo->findOneBy(['slug' => 'test-1']);
        $client->xmlHttpRequest('POST', '/ajax-comment', array('post' => $post, 'content' => 'test comment'));
        $this->assertEquals(200,  $client->getResponse()->getStatusCode());
    }

    public function testUserCanDeleteCommentPost()
    {
        $client = $this->loginUser();
        $client->request('GET', "/posts/test-1");
        $this->assertEquals(200,  $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isSuccessful());

        $container = self::$kernel->getContainer();
        $em = $container->get('doctrine')->getManager();
        $commentRepo = $em->getRepository('App:Comment');

        $postRepo = $em->getRepository('App:Post');
        $post = $postRepo->findOneBy(['slug' => 'test-1']);

        $comment = $commentRepo->findOneBy(['content' => 'test comment for delete', 'author' => 24]);

        $client->xmlHttpRequest('POST', '/ajax-comment', array('post' => $post, 'content' => 'test comment for delete'));

        $this->assertEquals(200,  $client->getResponse()->getStatusCode());

        $client->xmlHttpRequest('DELETE', '/ajax-delete', array('comment' => $comment));
    }
}