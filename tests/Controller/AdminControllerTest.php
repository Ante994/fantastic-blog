<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 13.01.19.
 * Time: 12:29
 */

namespace App\Tests\Controller;

use App\Tests\FixturesTestCase;
use Symfony\Component\HttpKernel\Client;

class AdminControllerTest extends FixturesTestCase
{
    /** @var Client $client */
    protected $client;

    public function setUp()
    {
        parent::setUp();
        $this->client = $this->loginAdmin();
    }

    /**
     * @dataProvider provideUrlsForAdmin
     * @param $url
     */
    public function testPageIsSuccessfulForAdmin($url)
    {

        $this->client->request('GET', $url);

        $this->assertEquals(200,  $this->client->getResponse()->getStatusCode());
    }

    public function testRegisterAdminSuccessfully()
    {
        $client = self::createClient();

        $client->request('GET', '/register');
        $client->submitForm('Submit', [
            'user[firstname]' => 'fantastic',
            'user[lastname]' => 'tester',
            'user[email]' => 'admin2@fb.com',
            'user[plainPassword][first]' => '1234',
            'user[plainPassword][second]' => '1234',
        ]);

        $this->assertStatusCode(302, $client);
    }


    public function testAdminCanClickOnNewPostCreate()
    {
        $crawler = $this->client->request('GET', "/admin/posts");
        $this->assertEquals(200,  $this->client->getResponse()->getStatusCode());

        $link = $crawler
            ->filter('a:contains("New post")')
            ->eq(0)
            ->link();

        $page = $this->client->click($link);
        $this->assertEquals('New post', $page->filter('h3')->first()->text());
    }

    public function testAdminCanCreateNewPost()
    {
        $crawler = $this->client->request('GET', "/admin/posts/new");
        $this->assertEquals(200,  $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Submit')->form();

        $form['post[postTranslation][titleEn]'] = 'This is title';
        $form['post[postTranslation][contentEn]'] = 'This is example content for testing!';
        $form['post[tags][1]'] = true;

        $this->client->submit($form);
        $this->assertEquals(302,  $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->request('GET', "/");
        $this->assertEquals(200,  $this->client->getResponse()->getStatusCode());
        $this->assertContains('This is title', $crawler->filter('h2')->first()->text());
    }

    public function testAdminCanClickOnNewTagCreate()
    {
        $crawler = $this->client->request('GET', "/admin/tags");
        $this->assertEquals(200,  $this->client->getResponse()->getStatusCode());

        $link = $crawler
            ->filter('a:contains("New tag")')
            ->eq(0)
            ->link();

        $page = $this->client->click($link);
        $this->assertEquals('New tag', $page->filter('h3')->first()->text());
    }

    public function testAdminCanCreateNewTag()
    {
        $crawler = $this->client->request('GET', "/admin/tags/new");
        $this->assertEquals(200,  $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Submit')->form();

        $form['tag[nameEn]'] = 'Tag X';
        $form['tag[nameHr]'] = 'Tag X-Hr';
        $this->client->submit($form);
        $this->assertEquals(200,  $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->request('GET', "/admin/tags");
        $this->assertEquals(200,  $this->client->getResponse()->getStatusCode());
        $this->assertContains('Tag X', $crawler->filter('tr')->last()->text());
    }

    public function testAdminCanEditPost()
    {
        $container = self::$kernel->getContainer();
        $em = $container->get('doctrine')->getManager();
        $postRepo = $em->getRepository('App:Post');
        $post = $postRepo->find(1);
        $crawler = $this->client->request('GET', "/admin/posts/".$post->getId()."/edit");
        $this->assertEquals(200,  $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Submit')->form();

        $form['post[postTranslation][titleEn]'] = 'This is edited title';
        $form['post[tags][1]'] = true;
        $form['post[tags][2]'] = true;

        $this->client->submit($form);
        $this->assertEquals(302,  $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->request('GET', "/");
        $this->assertEquals(200,  $this->client->getResponse()->getStatusCode());
        $this->assertContains('This is edited title', $crawler->filter('h2')->first()->text());
    }

    public function testAdminCanDeletePost()
    {
        $container = self::$kernel->getContainer();
        $em = $container->get('doctrine')->getManager();
        $postRepo = $em->getRepository('App:Post');
        $post = $postRepo->find(1);

        $this->client->request('GET', "admin/posts/".$post->getId());
        $this->assertEquals(302,  $this->client->getResponse()->getStatusCode());
        $this->client->request('DELETE', "/admin/posts/".$post->getId());
        $this->assertEquals(404,  $this->client->getResponse()->getStatusCode());
    }

    public function testAdminCanDeleteTag()
    {
        $container = self::$kernel->getContainer();
        $em = $container->get('doctrine')->getManager();
        $tagRepo = $em->getRepository('App:Tag');
        $tag = $tagRepo->find(1);

        $this->client->request('DELETE', "/admin/tags/".$tag->getId());
        $this->assertEquals(200,  $this->client->getResponse()->getStatusCode());
    }

    public function testAdminCanEditTag()
    {
        $crawler = $this->client->request('GET', "/admin/tags/1/edit");
        $this->assertEquals(200,  $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Submit')->form();

        $form['tag[nameEn]'] = 'Tag Edit';
        $this->client->submit($form);
        $this->assertEquals(302,  $this->client->getResponse()->getStatusCode());
        $this->client->request('GET', "/admin/tags");
        $this->assertEquals(200,  $this->client->getResponse()->getStatusCode());
    }

    public function provideUrlsForAdmin()
    {
        return array(
            array('/admin/tags/new'),
            array('/admin/posts/new'),
            array('/admin/tags/1'),
        );
    }

    private function loginAdmin()
    {
        return static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin@fb.com',
            'PHP_AUTH_PW'   => '1234',
            'HTTP_HOST' => 'fantastic-blog.puphpet'
        ));
    }
}