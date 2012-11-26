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

class CypressGitElephantHostExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'tree_object' => new \Twig_Filter_Method($this, 'treeObject')
        );
    }

    public function treeObject(TreeObject $treeObject)
    {
        return 'treeObject';
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
