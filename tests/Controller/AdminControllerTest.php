<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 13.01.19.
 * Time: 12:29
 */

namespace App\Tests\Controller;


use Liip\FunctionalTestBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    /**
     * @dataProvider provideUrlsForAdmin
     * @param $url
     */
    public function testPageIsSuccessfulForAdmin($url)
    {
        $client = $this->loginAdmin();
        $client->request('GET', $url);

        $this->assertEquals(200,  $client->getResponse()->getStatusCode());
    }

    public function testAdminCanClickOnNewPostCreate()
    {
        $client = $this->loginAdmin();
        $crawler = $client->request('GET', "/");
        $this->assertEquals(200,  $client->getResponse()->getStatusCode());

        $link = $crawler
            ->filter('a:contains("New post")')
            ->eq(0)
            ->link();

        $page = $client->click($link);
        $this->assertEquals('Create new post', $page->filter('h3')->first()->text());
    }

    public function testAdminCanCreateNewPost()
    {
        $client = $this->loginAdmin();
        $crawler = $client->request('GET', "/admin/posts/new");
        $this->assertEquals(200,  $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Submit')->form();

        $form['post[title]'] = 'This is title';
        $form['post[postDetail][content]'] = 'This is example content for testing!';
        $form['post[tags][1]'] = true;

        $client->submit($form);
        $this->assertEquals(302,  $client->getResponse()->getStatusCode());
        $crawler = $client->request('GET', "/");
        $this->assertEquals(200,  $client->getResponse()->getStatusCode());
        $this->assertContains('This is title', $crawler->filter('h2')->first()->text());
    }

    public function testAdminCanClickOnNewTagCreate()
    {
        $client = $this->loginAdmin();
        $crawler = $client->request('GET', "/");
        $this->assertEquals(200,  $client->getResponse()->getStatusCode());

        $link = $crawler
            ->filter('a:contains("New tag")')
            ->eq(0)
            ->link();

        $page = $client->click($link);
        $this->assertEquals('Create new tag', $page->filter('h3')->first()->text());
    }

    public function testAdminCanCreateNewTag()
    {
        $client = $this->loginAdmin();
        $crawler = $client->request('GET', "/admin/tags/new");
        $this->assertEquals(200,  $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Submit')->form();

        $form['tag[name]'] = 'Tag X';
        $client->submit($form);
        $this->assertEquals(302,  $client->getResponse()->getStatusCode());
        $crawler = $client->request('GET', "/admin/tags");
        $this->assertEquals(200,  $client->getResponse()->getStatusCode());
        $this->assertContains('Tag X', $crawler->filter('h2')->first()->text());
    }

    public function testAdminUserCanEditPost()
    {
        $client = $this->loginAdmin();

        $container = self::$kernel->getContainer();
        $em = $container->get('doctrine')->getManager();
        $postRepo = $em->getRepository('App:Post');
        $post = $postRepo->find(1);
        $crawler = $client->request('GET', "/admin/posts/".$post->getSlug."/edit");
        $this->assertEquals(200,  $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Submit')->form();

        $form['post[title]'] = 'This is edited title';
        $form['post[tags][1]'] = true;
        $form['post[tags][2]'] = true;

        $client->submit($form);
        $this->assertEquals(302,  $client->getResponse()->getStatusCode());
        $crawler = $client->request('GET', "/");
        $this->assertEquals(200,  $client->getResponse()->getStatusCode());
        $this->assertContains('This is edited title', $crawler->filter('h2')->first()->text());
    }

    public function testAdminUserCanDeletePost()
    {
        $client = $this->loginAdmin();

        $container = self::$kernel->getContainer();
        $em = $container->get('doctrine')->getManager();
        $postRepo = $em->getRepository('App:Post');
        $post = $postRepo->findOneBy(['author' => 1]);
        $client->request('GET', "/posts/".$post->getSlug());
        $this->assertEquals(200,  $client->getResponse()->getStatusCode());
        $client->request('DELETE', "/admin/posts/".$post->getSlug());
        $this->assertEquals(302,  $client->getResponse()->getStatusCode());
    }



    public function testAdminUserCanDeleteTag()
    {
        $client = $this->loginAdmin();

        $container = self::$kernel->getContainer();
        $em = $container->get('doctrine')->getManager();
        $tagRepo = $em->getRepository('App:Tag');
        $tag = $tagRepo->find(1);
        $client->request('DELETE', "/admin/tags/".$tag->getId());
        $this->assertEquals(302,  $client->getResponse()->getStatusCode());
    }


    public function testAdminUserCanEditTag()
    {
        $client = $this->loginAdmin();
        $crawler = $client->request('GET', "/admin/tags/1");
        $this->assertEquals(200,  $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Submit')->form();

        $form['tag[name]'] = 'Tag First';
        $client->submit($form);
        $this->assertEquals(302,  $client->getResponse()->getStatusCode());
        $crawler = $client->request('GET', "/admin/tags");
        $this->assertEquals(200,  $client->getResponse()->getStatusCode());
        $this->assertContains('Tag First', $crawler->filter('h2')->first()->text());
    }


    public function provideUrlsForAdmin()
    {
        return array(
            array('/admin/tags/new'),
            array('/admin/posts/new'),
        );
    }

    public function loginAdmin()
    {
        return static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin@fb.com',
            'PHP_AUTH_PW'   => '1234',
            'HTTP_HOST' => 'fantastic-blog.puphpet'
        ));
    }

}