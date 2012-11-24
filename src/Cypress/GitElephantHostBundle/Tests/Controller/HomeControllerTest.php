<?php

namespace Cypress\GitElephantHostBundle\Tests\Controller;

use Cypress\GitElephantHostBundle\Tests\GitElephantHostTestCase;

class HomeControllerTest extends GitElephantHostTestCase
{
    public function testHomepage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertCount(1, $crawler->filter('h1:contains("Git Repositories")'));
    }
}
