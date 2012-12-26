<?php

namespace Cypress\GitElephantHostBundle\Tests\Controller;

use Cypress\GitElephantHostBundle\Tests\GitElephantHostTestCase;

class RepositoryControllerTest extends GitElephantHostTestCase
{
    public function setUp()
    {
        $this->loadFixtures(array(
            'Cypress\GitElephantHostBundle\DataFixtures\ORM\LoadRepositoryData'
        ));
    }

    public function testHomepage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertCount(1, $crawler->filter('h1:contains("Git Repositories")'));
        $this->assertCount(2, $crawler->filter('ul.repository_list li'));
    }
}
