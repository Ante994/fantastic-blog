<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 03.01.19.
 * Time: 11:54
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    public function testShowPostIndexPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', "/");

        $this->assertEquals(200,  $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Fantastic blog")')->count()
        );
    }

    public function testPageContainsTitle()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', "/");

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Fantastic blog")')->count()
        );
    }

    /**
     * @dataProvider provideUrls
     * @param $url
     */
    public function testPagesIsSuccessfulOpened($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function provideUrls()
    {
        return array(
            array('/'),
            array('/login'),
            array('/register'),
        );
    }


    /**
     * @dataProvider provideUrlsForAdmin
     * @param $url
     */
    public function testPageIsFalseForDefaultUser($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertFalse($client->getResponse()->isSuccessful());
    }


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

    public function provideUrlsForAdmin()
    {
        return array(
            array('/tags/new'),
            array('/posts/new'),
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


    public function testAdminSubmitPostForm()
    {
        $client = $this->loginAdmin();
        $crawler = $client->request('GET', "/posts/new");
        $this->assertEquals(200,  $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Submit')->form();

        $form['post[title]'] = '';
        $form['post[postDetail][content]'] = 'Hey there!';

        $client->submit($form);


    }

    public function testUserCanRegister()
    {
        $client = self::createClient();
        $client->request('GET', '/register');
        $client->submitForm('Submit', [
            'user[firstname]' => 'fantastic',
            'user[lastname]' => 'tester',
            'user[email]' => 'ante@fb.com',
            'user[plainPassword][first]' => '1234',
            'user[plainPassword][second]' => '1234',
        ]);

    }
}