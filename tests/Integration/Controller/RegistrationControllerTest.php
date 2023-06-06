<?php

namespace App\Tests\Integration\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    /**
     * @group include
     */
    public function testRegisterCorrect()
    {
        /*
          * test registration
          */
        $client = static::createClient();
        $crawler = $client->request('GET', '/home');
        //we should be automatically redirected to the welcome page (status code 302)
        $this->assertEquals(302, $client->getResponse()->getStatusCode(), 'you should have been redirected to the welcome page');
        //follow the redirect
        $crawler = $client->followRedirect();
        //make sure we are on welcome page
        $this->assertSelectorTextContains('title', 'Welcome');

        //click the register link
        $linkPosition = 4;
        $link = $crawler->filter('a')->eq($linkPosition); //of all the links, register is the fourth one
        $crawler = $client->click($link->link());
        //make sure we are on the register page
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('title', 'Register');

        //fill in the form
        $form = $crawler->filter('form[name="registration_form"]')->form();
        $fields = $form->all();
        $fieldNames = array_keys($fields);
        $form['registration_form[first_name]'] = "testname";
        $form['registration_form[last_name]'] = "testname";
        $form['registration_form[username]'] = "testname";
        $form['registration_form[email]'] = "testregister@test.com";
        $form['registration_form[plainPassword][first]'] = "password";
        $form['registration_form[plainPassword][second]'] = "password";
        $form["registration_form[agree_terms]"] = true;
        $client->submit($form);
        $crawler = $client->followRedirect(); //2 redirections because it counts the "going to welcome" as a seperate redirect
        $crawler = $client->followRedirect();
        //make sure we got to the welcome page
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('title', 'Welcome');

        //try to log in with our new account
        //open the popup
        $login_button = $crawler->selectLink('Login')->link();
        $crawler = $client->click($login_button);
        //make sure it is opened
        $this->assertSelectorTextContains('p', 'Log into your account');

        //fill in the form
        $form = $crawler->filter('#login_form')->form();
        $form['_username'] = "testregister@test.com";
        $form['_password'] = "password";
        $client->submit($form);
        $crawler = $client->followRedirect();
        //make sure we went to Home
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('title', 'Home');
        $this->assertSelectorTextContains('h1', 'Recommended books for you!');

        //remove the user we just added from the database
        $userManager = $this->getContainer()->get('doctrine')->getManager();
        $testRegisterObject = $userManager->getRepository(User::class)->findOneBy(['email' => "testregister@test.com"]);
        $userManager->remove($testRegisterObject);
        $userManager->flush();

    }

    /**
     * @group include
     */
    public function testRegisterWrongPassword()
    {
        /*
         * test registration with mismatching password
         */

        $client = static::createClient();
        $crawler = $client->request('GET', '/home');
        //we should be automatically redirected to the welcome page (status code 302)
        $this->assertEquals(302, $client->getResponse()->getStatusCode(), 'you should have been redirected to the welcome page');
        //follow the redirect
        $crawler = $client->followRedirect();
        //make sure we are on welcome page
        $this->assertSelectorTextContains('title', 'Welcome');

        //click the register link
        $linkPosition = 4;
        $link = $crawler->filter('a')->eq($linkPosition); //of all the links, register is the fourth one
        $crawler = $client->click($link->link());
        //make sure we are on the register page
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('title', 'Register');

        //fill in the form
        $form = $crawler->filter('form[name="registration_form"]')->form();
        $fields = $form->all();
        $fieldNames = array_keys($fields);
        $form['registration_form[first_name]'] = "testname";
        $form['registration_form[last_name]'] = "testname";
        $form['registration_form[username]'] = "testname";
        $form['registration_form[email]'] = "testregister@test.com";
        $form['registration_form[plainPassword][first]'] = "password";
        $form['registration_form[plainPassword][second]'] = "wrongPassword";
        $form["registration_form[agree_terms]"] = true;
        $client->submit($form);

        //make sure we remained on the register page
        $this->assertSelectorTextContains('title', 'Register');
        //check that the user gets the text showing that their passwords don't match
        $this->assertSelectorTextContains('#register_fields > div > ul > li', 'The password fields must match.');
    }

    /**
     * @group include
     */
    public function testRegisterUnacceptedTerms()
    {
        /*
         * test registration without accepting the terms
         */

        $client = static::createClient();
        $crawler = $client->request('GET', '/home');
        //we should be automatically redirected to the welcome page (status code 302)
        $this->assertEquals(302, $client->getResponse()->getStatusCode(), 'you should have been redirected to the welcome page');
        //follow the redirect
        $crawler = $client->followRedirect();
        //make sure we are on welcome page
        $this->assertSelectorTextContains('title', 'Welcome');

        //click the register link
        $linkPosition = 4;
        $link = $crawler->filter('a')->eq($linkPosition); //of all the links, register is the fourth one
        $crawler = $client->click($link->link());
        //make sure we are on the register page
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('title', 'Register');

        //fill in the form
        $form = $crawler->filter('form[name="registration_form"]')->form();
        $fields = $form->all();
        $fieldNames = array_keys($fields);
        $form['registration_form[first_name]'] = "testname";
        $form['registration_form[last_name]'] = "testname";
        $form['registration_form[username]'] = "testname";
        $form['registration_form[email]'] = "testregister@test.com";
        $form['registration_form[plainPassword][first]'] = "password";
        $form['registration_form[plainPassword][second]'] = "password";
        $client->submit($form);
        //make sure we remained on the registration page
        $this->assertSelectorTextContains('title', 'Register');


    }

    /**
     * @group include
     */
    public function testRegisterUnfilledField()
    {
        /*
         * test that you can't register if any of the regular fields are not filled in
         */

        $client = static::createClient();
        $crawler = $client->request('GET', '/home');
        //we should be automatically redirected to the welcome page (status code 302)
        $this->assertEquals(302, $client->getResponse()->getStatusCode(), 'you should have been redirected to the welcome page');
        //follow the redirect
        $crawler = $client->followRedirect();
        //make sure we are on welcome page
        $this->assertSelectorTextContains('title', 'Welcome');

        //click the register link
        $linkPosition = 4;
        $link = $crawler->filter('a')->eq($linkPosition); //of all the links, register is the fourth one
        $crawler = $client->click($link->link());
        //make sure we are on the register page
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('title', 'Register');

        //fill in the form
        $form = $crawler->filter('form[name="registration_form"]')->form();
        $fields = $form->all();
        $fieldNames = array_keys($fields);
        $form['registration_form[first_name]'] = "";
        $form['registration_form[last_name]'] = "testname";
        $form['registration_form[username]'] = "testname";
        $form['registration_form[email]'] = "testregister@test.com";
        $form['registration_form[plainPassword][first]'] = "password";
        $form['registration_form[plainPassword][second]'] = "wrongPassword";
        $form["registration_form[agree_terms]"] = true;
        $client->submit($form);

        //make sure we remained on the register page
        $this->assertSelectorTextContains('title', 'Register');
    }
}