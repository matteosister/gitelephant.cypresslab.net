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
use PygmentsElephant\Pygmentize;
use Symfony\Component\Filesystem\Filesystem;

class Content extends Service
{
    /**
     * @var Pygmentize
     */
    private $pygmentize;

    /**
     * Class constructor
     *
     * @param \Symfony\Component\HttpFoundation\Request $request         request
     * @param \Doctrine\ODM\MongoDB\DocumentManager     $documentManager document manager
     * @param \PygmentsElephant\Pygmentize              $pygmentize      pygmentize
     */
    public function __construct(Request $request, DocumentManager $documentManager, Pygmentize $pygmentize)
    {
        $this->request = $request;
        $this->documentManager = $documentManager;
        $this->pygmentize = $pygmentize;
    }

    public function outputContent(TreeObject $treeObject)
    {
        $rawContent = implode("\n", $this->getGit()->outputContent($treeObject, 'HEAD'));

        return $this->pygmentize->format($rawContent, $treeObject->getName());
    }
}
