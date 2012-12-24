<?php
/**
 * User: matteo
 * Date: 29/11/12
 * Time: 23.11
 * 
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Git;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Routing\RouterInterface;
use Cypress\GitElephantHostBundle\Git\Base\Service;
use GitElephant\Objects\TreeObject;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Git route generator
 */
class Router extends Service
{

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @var RefPathSplitter
     */
    private $splitter;

    /**
     * Class constructor
     *
     * @param \Symfony\Component\HttpFoundation\Request  $request       request
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager document manager
     * @param \Symfony\Component\Routing\RouterInterface $router        router
     * @param RefPathSplitter                            $splitter      ref/path splitter
     */
    public function __construct(Request $request, ObjectManager $objectManager, RouterInterface $router, RefPathSplitter $splitter)
    {
        $this->request = $request;
        $this->objectManager = $objectManager;
        $this->router = $router;
        $this->splitter = $splitter;
    }

    /**
     * tree object url
     *
     * @param \GitElephant\Objects\TreeObject $treeObject
     *
     * @return string
     */
    public function treeObjectUrl(TreeObject $treeObject)
    {
        $parts = $this->splitter->split($this->getRepository()->getGit(), $this->request->attributes->get('ref'), $treeObject->getFullPath());

        return $this->router->generate('tree_object', array(
            'slug' => $this->getRepository()->getSlug(),
            'ref' => $parts[0],
            'path' => $parts[1]
        ));
    }

    /**
     * get the current route slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->getRepository()->getSlug();
    }
}
