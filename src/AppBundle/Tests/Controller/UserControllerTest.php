<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testLoginRoute()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        // Create a new entry in the database
        $crawler = $client->request('GET', '/login');
      
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /login");
    }
  
    public function testRegisterRoute()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        // Create a new entry in the database
        $crawler = $client->request('GET', '/register');
      
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /register/");
    }
  
    public function testResettingRoute()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        // Create a new entry in the database
        $crawler = $client->request('GET', '/resetting/request');
      
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /resetting/request");
      
        $form = $crawler->selectButton('Reset password')->form(array(
            'username'  => 'Foo'
        ));
      
        $client->submit($form);
      
        $this->assertEquals(0, $crawler->filter('[value="Foo"]')->count(), 'Missing element [value="Foo"]');
    }
  
    public function testSchedulerRoute()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        // Create a new entry in the database
        $crawler = $client->request('GET', '/command-scheduler/list');
      
      //Redirect to login (only super admin)
        $this->assertEquals(302, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /command-scheduler/list");
    }
}
