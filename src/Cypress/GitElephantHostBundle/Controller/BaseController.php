<?php
/**
 * User: matteo
 * Date: 22/11/12
 * Time: 23.03
 *
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ODM\MongoDB\DocumentManager;

class BaseController extends Controller
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
