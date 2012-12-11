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
use GitElephant\Objects\TreeObject;
use PygmentsElephant\Pygmentize;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Git Content
 */
class Content extends Service
{
    const JPG = '\xFF\xD8\xFF';
    const GIF  = 'GIF';
    const PNG  = '\x89\x50\x4e\x47\x0d\x0a\x1a\x0a';

    /**
     * @var Pygmentize
     */
    private $pygmentize;

    /**
     * Class constructor
     *
     * @param Request       $request       request
     * @param ObjectManager $objectManager document manager
     * @param Pygmentize    $pygmentize    pygmentize
     */
    public function __construct(Request $request, ObjectManager $objectManager, Pygmentize $pygmentize)
    {
        $this->request = $request;
        $this->objectManager = $objectManager;
        $this->pygmentize = $pygmentize;
    }

    /**
     * output git treeobject content
     *
     * @param \GitElephant\Objects\TreeObject $treeObject
     *
     * @return string
     */
    public function outputContent(TreeObject $treeObject)
    {
        $rawContent = implode("\n", $this->getGit()->outputContent($treeObject, 'HEAD'));

        return $this->pygmentize->format($rawContent, $treeObject->getName());
    }
}
