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

class Service
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
     * @return \Cypress\GitElephantHostBundle\Document\Repository
     */
    protected function getRepository()
    {
        return $this->objectManager
            ->getRepository('CypressGitElephantHostBundle:Repository')
            ->findOneBy(array(
                'slug' => $this->request->attributes->get('slug')
            ));
    }

    /**
     * @return Repository
     */
    protected function getGit()
    {
        return $this->getRepository()->getGit();
    }
}
