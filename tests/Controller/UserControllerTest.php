<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 11.01.19.
 * Time: 16:33
 */

namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private function loginUser()
    {
        return static::createClient(array(), array(
            'PHP_AUTH_USER' => 'ante@fb.com',
            'PHP_AUTH_PW'   => '1234',
            'HTTP_HOST' => 'fantastic-blog.puphpet'
        ));
    }

    public function testNonAuthenticatedUserCannotAccessProfilePage()
    {
        $client = static::createClient();
        $client->request('GET', "/profile");

        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/login'));
    }

    public function testUserProfileAvailability()
    {
        $client = $this->loginUser();
        $client->request('GET', "/profile");

        $this->assertContains(
            'ANTE TESTER',
            $client->getResponse()->getContent()
        );
    }

    public function testUserCanChangeUserData()
    {
        $client = $this->loginUser();
        $client->followRedirects();
        $client->request('GET', "/profile/edit/1");

        $client->submitForm('Submit', [
            'user[firstname]' => 'Antisa',
            'user[lastname]' => 'Testira',
            'user[email]' => 'ante@fb.com',
            'user[plainPassword][first]' => '1234',
            'user[plainPassword][second]' => '1234',
        ]);

        $this->assertContains(
            'Antisa',
            $client->getResponse()->getContent()
        );
    }

    public function testNotLoggedUserCannotAccessEditPage()
    {
        $client = static::createClient();
        $client->request('GET', "/profile/edit/2");

        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/login'));
    }

    public function testLoggedUserCannotAccessAnotherUserEditPage()
    {
        $client = $this->loginUser();
        $client->request('GET', "/profile/edit/2");

        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/login'));
    }

}