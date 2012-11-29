<?php
/**
 * User: matteo
 * Date: 29/11/12
 * Time: 23.11
 * 
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Git;

use Cypress\GitElephantBundle\Collection\GitElephantRepositoryCollection;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Symfony\Component\Routing\RouterInterface;
use GitElephant\Repository;
use Cypress\GitElephantHostBundle\Git\Base\Service;

class Router extends Service
{

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * Class constructor
     *
     * @param \Symfony\Component\HttpFoundation\Request  $request         request
     * @param \Doctrine\ODM\MongoDB\DocumentManager      $documentManager document manager
     * @param \Symfony\Component\Routing\RouterInterface $router          router
     */
    public function __construct(Request $request, DocumentManager $documentManager, RouterInterface $router)
    {
        $this->request = $request;
        $this->documentManager = $documentManager;
        $this->router = $router;
    }

    /**
     * @return string
     */
    public function parentUrl()
    {
        $path = $this->request->attributes->get('path');
        $newPath = substr($path, 0, strrpos($path, '/'));
        $params = array(
            'slug' => 'first-repository'
        );
        if ('' == $newPath) {
            $route = 'repository';
        } else {
            $route = 'tree_object';
            $params['ref'] = 'master';
            $params['path'] = substr($path, 0, strrpos($path, '/'));
        }

        return $this->router->generate($route, $params);
    }
}
