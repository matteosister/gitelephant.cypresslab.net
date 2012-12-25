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
use GitElephant\Objects\Diff\DiffChunkLine;
use GitElephant\Objects\Diff\DiffChunk;
use GitElephant\Objects\Tree;
use GitElephant\Objects\Commit;

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
            'output_chunk' => new \Twig_Function_Method($this, 'outputChunk', array('is_safe' => array('html'))),
            'icon_for' => new \Twig_Function_Method($this, 'iconFor', array('is_safe' => array('html'))),
            'is_image' => new \Twig_Function_Method($this, 'isImage', array('is_safe' => array('html'))),
            'commit_box' => new \Twig_Function_Method($this, 'commitBox', array('is_safe' => array('html'))),
            'code_table' => new \Twig_Function_Method($this, 'codeTable', array('is_safe' => array('html')))
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
     * output a line
     *
     * @param \GitElephant\Objects\Diff\DiffChunk $diffChunk diff chunk
     *
     * @return mixed
     */
    public function outputChunk(DiffChunk $diffChunk)
    {
        return $this->container->get('cypress.git_elephant_host.git_content')->outputChunk($diffChunk);
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
     * is an image
     *
     * @param \GitElephant\Objects\Tree $tree tree
     *
     * @return bool
     */
    public function isImage(Tree $tree)
    {
        if (!$tree->isBinary()) {
            return false;
        }
        $pathInfo = pathinfo($tree->getSubject()->getName());
        if (!isset($pathInfo['extension'])) {
            return false;
        }

        return in_array($pathInfo['extension'], array('jpg', 'jpeg', 'png', 'gif'));
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
     * render a commit box
     *
     * @param \GitElephant\Objects\Commit $commit commit
     * @param bool                        $link   add commit link
     *
     * @return mixed
     */
    public function commitBox(Commit $commit, $link = false)
    {
        return $this->container->get('templating')->render('CypressGitElephantHostBundle:Commit:box.html.twig', array(
            'slug' => $this->container->get('cypress.git_elephant_host.git_router')->getSlug(),
            'commit' => $commit,
            'link' => $link
        ));
    }

    public function codeTable(DiffChunk $diffChunk)
    {
        return $this->container->get('templating')->render('CypressGitElephantHostBundle:Commit:code_table.html.twig', array(
            'lines' => $diffChunk->getLines()
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
