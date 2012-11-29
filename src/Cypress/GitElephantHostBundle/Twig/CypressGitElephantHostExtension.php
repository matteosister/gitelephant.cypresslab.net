<?php
/**
 * User: matteo
 * Date: 26/11/12
 * Time: 22.14
 *
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Twig;

use GitElephant\Objects\TreeObject;
use Symfony\Component\DependencyInjection\ContainerInterface;
use GitElephant\Repository;

class CypressGitElephantHostExtension extends \Twig_Extension
{
    /**
     * @var ContainerInterface
     */
    private $container;

    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFilters()
    {
        return array(
            'link_tree_object' => new \Twig_Filter_Method($this, 'linkTreeObject')
        );
    }

    public function getFunctions()
    {
        return array(
            'link_parent' => new \Twig_Function_Method($this, 'linkParent')
        );
    }


    /**
     * Generates an url from a treeObject
     *
     * @param \GitElephant\Objects\TreeObject $treeObject
     *
     * @return mixed
     */
    public function linkTreeObject(TreeObject $treeObject)
    {
        return $this->container->get('router')->generate('tree_object', array(
            'slug' => 'first-repository',
            'ref' => 'master',
            'path' => $treeObject->getFullPath()
        ));
    }

    public function linkParent(Repository $git, $path)
    {
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
        
        return $this->container->get('router')->generate($route, $params);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'cypress_git_elephant_host';
    }
}
