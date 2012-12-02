<?php
/**
 * User: matteo
 * Date: 30/11/12
 * Time: 0.06
 * 
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Git;

use Cypress\GitElephantHostBundle\Git\Base\Service;
use Cypress\GitElephantBundle\Collection\GitElephantRepositoryCollection;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ODM\MongoDB\DocumentManager;
use GitElephant\Objects\TreeObject;

class Content extends Service
{
    /**
     * Class constructor
     *
     * @param \Symfony\Component\HttpFoundation\Request $request         request
     * @param \Doctrine\ODM\MongoDB\DocumentManager     $documentManager document manager
     */
    public function __construct(Request $request, DocumentManager $documentManager)
    {
        $this->request = $request;
        $this->documentManager = $documentManager;
    }

    public function outputContent(TreeObject $treeObject)
    {
        return implode("\n", $this->getGit()->outputContent($treeObject, 'HEAD'));
    }
}
