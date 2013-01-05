<?php
/**
 * User: matteo
 * Date: 04/01/13
 * Time: 22.22
 * 
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Command\Base;

use Cypress\GitElephantHostBundle\Entity\Repository\RepositoryRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

/**
 * base command
 */
class BaseCommand extends ContainerAwareCommand
{
    /**
     * @return RepositoryRepository
     */
    protected function getRepoRepository()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('Cypress\GitElephantHostBundle\Entity\Repository');
    }

    /**
     * @return EntityManager
     */
    protected function getEM()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
