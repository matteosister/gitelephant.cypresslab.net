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

    /**
     * class constructor
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container container
     */
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
            'link_parent' => new \Twig_Function_Method($this, 'linkParent'),
            'output_content' => new \Twig_Function_Method($this, 'outputContent')
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
        return $this->container->get('cypress.git_elephant_host.git_router')->treeObjectUrl($treeObject);
    }

    public function linkParent()
    {
        return $this->container->get('cypress.git_elephant_host.git_router')->parentUrl();
    }

    public function outputContent($path)
    {
        return $this->container->get('cypress.git_elephant_host.git_content')->outputContent($path);
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
