<?php
/**
 * User: matteo
 * Date: 24/11/12
 * Time: 23.55
 *
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Tests\Document;

use Cypress\GitElephantHostBundle\Tests\GitElephantHostTestCase;

class RepositoryTest extends GitElephantHostTestCase
{
    public function setUp()
    {
        $this->loadData();
    }

    public function testEventListener()
    {
        $this->assertCount(1, $this->getRepositoryRepository()->findAll());
        $repo = $this->getRepositoryRepository()->findOneBy(array());
        $this->assertInstanceOf('GitElephant\Repository', $repo->getGit());
    }
}
