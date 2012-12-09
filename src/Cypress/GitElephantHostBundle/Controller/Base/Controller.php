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
use GitElephant\Repository;
use Doctrine\ORM\EntityManager;


/**
 * base controller
 */
class Controller extends BaseController
{
    /**
     * @return DocumentManager
     */
    public function getDM()
    {
        return $this->get('doctrine.odm.mongodb.document_manager');
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentRepository
     */
    public function getRepositoryRepo()
    {
        return $this->getEM()->getRepository('CypressGitElephantHostBundle:Repository');
    }

    /**
     * @return EntityManager
     */
    public function getEM()
    {
        return $this->get('doctrine.orm.entity_manager');
    }

    /**
     * @param string $slug repository slug
     *
     * @return Repository
     */
    public function getGit($slug)
    {
        return $this->getRepositoryRepo()->findOneBy(array('slug' => $slug))->getGit();
    }
}
