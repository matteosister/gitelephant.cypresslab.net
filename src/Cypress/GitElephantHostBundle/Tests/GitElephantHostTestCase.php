<?php
/**
 * User: matteo
 * Date: 24/11/12
 * Time: 23.53
 *
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Tests;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Doctrine\Bundle\MongoDBBundle\Command\LoadDataFixturesDoctrineODMCommand;

class GitElephantHostTestCase extends WebTestCase
{
    protected function loadData()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new LoadDataFixturesDoctrineODMCommand());
        $command = $application->find('doctrine:mongodb:fixtures:load');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    protected function getDM()
    {
        return $this->getContainer()->get('doctrine_mongodb');
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentRepository
     */
    protected function getRepositoryRepository()
    {
        return $this->getDM()->getRepository('CypressGitElephantHostBundle:Repository');
    }
}
