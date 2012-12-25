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
use Doctrine\ORM\EntityRepository;


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
     * @return EntityRepository
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
     * get a repository
     *
     * @param string $slug repository slug
     *
     * @return \Cypress\GitElephantHostBundle\Entity\Repository
     */
    public function getRepository($slug)
    {
        return $this->getRepositoryRepo()->findOneBy(array('slug' => $slug));
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
