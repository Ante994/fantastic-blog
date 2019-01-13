<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 13.01.19.
 * Time: 12:30
 */

namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{

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

    /**
     * @dataProvider provideUrlsHrvTranslation
     * @param $url
     */
    public function testPagesIsSuccessfulOpenedHrvTranslation($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }


    public function testRegistrationValidations()
    {
        $client = self::createClient();

        $client->request('GET', '/register');
        $client->submitForm('Submit', [
            'user[firstname]' => 'test',
            'user[lastname]' => 'tester',
            'user[email]' => 'admin@fb.com',
            'user[plainPassword][first]' => '1234',
            'user[plainPassword][second]' => '1234',
        ]);

        $this->assertContains(
            'Email is already used',
            $client->getResponse()->getContent()
        );
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


    public function testUserAdminCanLoginWithCorrectData()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', "/login");
        $this->assertEquals(200,  $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Submit')->form();

        $form['email'] = 'admin@fb.com';
        $form['password'] = '1234';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/'));

    }

    public function testUserCannotLogWithIncorrectData()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', "/login");
        $this->assertEquals(200,  $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Submit')->form();

        $form['email'] = 'notexist@fb.com';
        $form['password'] = '12345';

        $client->submit($form);

        $this->assertFalse($client->getResponse()->isRedirect('/'));
        $this->assertTrue($client->getResponse()->isRedirect('/login'));
    }

    public function provideUrls()
    {
        return array(
            array('/login'),
            array('/register'),
        );
    }

    public function provideUrlsHrvTranslation()
    {
        return array(
            array('/hr/prijava'),
            array('/hr/registracija'),
        );
    }
}
