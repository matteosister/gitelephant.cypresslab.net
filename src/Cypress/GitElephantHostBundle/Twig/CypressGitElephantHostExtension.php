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
use GitElephant\Objects\TreeishInterface;

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
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * twig filters
     *
     * @return array
     */
    public function getFilters()
    {
        return array(
            'link_tree_object' => new \Twig_Filter_Method($this, 'linkTreeObject'),
            'breadcrumb'       => new \Twig_Filter_Method($this, 'breadcrumb')
        );
    }

    /**
     * twig functions
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'link_parent' => new \Twig_Function_Method($this, 'linkParent'),
            'output_content' => new \Twig_Function_Method($this, 'outputContent', array('is_safe' => array('html'))),
            'icon_for' => new \Twig_Function_Method($this, 'iconFor', array('is_safe' => array('html')))
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

    /**
     * link to the parent
     *
     * @return mixed
     */
    public function linkParent()
    {
        return $this->container->get('cypress.git_elephant_host.git_router')->parentUrl();
    }

    /**
     * output a tree object content
     *
     * @param \GitElephant\Objects\TreeObject $treeObject tree object
     *
     * @return mixed
     */
    public function outputContent(TreeObject $treeObject)
    {
        return $this->container->get('cypress.git_elephant_host.git_content')->outputContent($treeObject);
    }

    /**
     * html for an icon (branch or tag)
     *
     * @param TreeishInterface $obj object
     *
     * @return string
     */
    public function iconFor($obj)
    {
        if (is_a($obj, 'GitElephant\Objects\TreeBranch')) {
            return '<i class="icon icon-leaf"></i>';
        }
        if (is_a($obj, 'GitElephant\Objects\TreeTag')) {
            return '<i class="icon icon-tag"></i>';
        }

        return '';
    }

    /**
     * generates an array of paths from a single path
     *
     * @param string $path path
     *
     * @return array
     */
    public function breadcrumb($path)
    {
        if ('' === $path) {
            return array();
        }
        $pathParts = explode('/', $path);
        $output = '';
        $arrOutput = array();
        foreach ($pathParts as $i => $folder) {
            $output .= $folder.'/';
            $arrOutput[$i]['path'] = rtrim($output, '/');
            $arrOutput[$i]['label'] = $folder;
        }

        return $arrOutput;
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
