<?php
/**
 * User: matteo
 * Date: 30/11/12
 * Time: 0.11
 * 
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Git\Base;

use Doctrine\ODM\MongoDB\DocumentRepository;
use GitElephant\Repository;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Persistence\ObjectManager;

class GitBaseService
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Cypress\GitElephantHostBundle\Entity\Repository
     */
    private $repository;

    /**
     * @return \Cypress\GitElephantHostBundle\Entity\Repository
     */
    protected function getRepository()
    {
        if (null === $this->repository) {
            $this->repository = $this->objectManager
                ->getRepository('CypressGitElephantHostBundle:Repository')
                ->findOneBy(array(
                    'slug' => $this->request->attributes->get('slug')
                ));
        }

        return $this->repository;
    }

    /**
     * @return Repository
     */
    protected function getGit()
    {
        return $this->getRepository()->getGit();
    }
}
