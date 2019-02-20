<?php

/*
 * This file is part of the TodoList package.
 * (c) Aleksey Mihayluk <sidlerbiz@gmail.com>
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TodoControllerTest extends WebTestCase
{
    public function testListAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/todos');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Todo List', $crawler->filter('h2')->text());
    }

    public function testCreateAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/todo/create');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Create Todo', $crawler->filter('h2')->text());
    }
}
