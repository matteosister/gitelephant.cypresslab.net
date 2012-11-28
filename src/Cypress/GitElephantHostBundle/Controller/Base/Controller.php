<?php
/**
 * User: matteo
 * Date: 22/11/12
 * Time: 23.03
 *
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Doctrine\ODM\MongoDB\DocumentManager;

class Controller extends BaseController
{
    /**
     * @return DocumentManager
     */
    public function getDM()
    {
        return $this->get('doctrine_mongodb');
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentRepository
     */
    public function getRepositoryRepo()
    {
        return $this->getDM()->getRepository('CypressGitElephantHostBundle:Repository');
    }
}
