<?php
/**
 * User: matteo
 * Date: 22/11/12
 * Time: 23.03
 *
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Controller\Base;

use Cypress\GitElephantHostBundle\Entity\Repository\UserRepository;
use Cypress\GitElephantHostBundle\Entity\User;
use Cypress\GitElephantHostBundle\Git\Cloner;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Doctrine\ODM\MongoDB\DocumentManager;
use GitElephant\Repository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Cypress\GitElephantHostBundle\Git\RefPathSplitter;
use Symfony\Component\HttpFoundation\Session\Session;


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
     * gets the actual user
     *
     * @return User|void
     */
    public function getUser()
    {
        if (null === $userId = $this->getRequest()->cookies->get('user')) {
            return null;
        }

        return $this->getUserRepository()->findOneBy(array('id' => $userId));
    }

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        return $this->getUser() !== null;
    }

    /**
     * user repository
     *
     * @return UserRepository
     */
    public function getUserRepository()
    {
        return $this->getEM()->getRepository('Cypress\GitElephantHostBundle\Entity\User');
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

    /**
     * @return RefPathSplitter
     */
    public function getRefPathSplitter()
    {
        return $this->get('ref_path.splitter');
    }

    /**
     * @return Logger
     */
    public function getLogger()
    {
        return $this->get('logger');
    }

    /**
     * @return Session
     */
    public function getSession()
    {
        return $this->get('session');
    }

    /**
     * @return \Cypress\GitElephantHostBundle\Github\User
     */
    public function getGithubUser()
    {
        return $this->get('cypress.git_elephant_host.github.user');
    }

    /**
     * @return Cloner
     */
    public function getCloner()
    {
        return $this->get('cypress.git_elephant_host_bundle.git.cloner');
    }
}
