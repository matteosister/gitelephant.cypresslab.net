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

class Service
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var \Doctrine\ODM\MongoDB\DocumentManager
     */
    protected $documentManager;

    /**
     * @return \Cypress\GitElephantHostBundle\Document\Repository
     */
    protected function getRepository()
    {
        return $this->documentManager
            ->getRepository('CypressGitElephantHostBundle:Repository')
            ->findOneBySlug($this->request->attributes->get('slug'));
    }

    /**
     * @return Repository
     */
    protected function getGit()
    {
        return $this->getRepository()->getGit();
    }
}
