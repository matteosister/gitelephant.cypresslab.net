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

    public function linkTreeObject(TreeObject $treeObject)
    {
        return $this->container->get('router')->generate('tree_object', array(
            'slug' => 'first-repository',
            'ref' => 'master',
            'path' => $treeObject->getFullPath()
        ));
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
