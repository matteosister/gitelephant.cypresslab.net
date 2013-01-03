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

/**
 * twig ext
 */
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
            'link_tree_object' => new \Twig_Filter_Method($this, 'linkTreeObject', array('is_safe' => array('all'))),
            'breadcrumb'       => new \Twig_Filter_Method($this, 'breadcrumb', array('is_safe' => array('html')))
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
            'is_text' => new \Twig_Function_Method($this, 'isPygmentableText', array('is_safe' => array('html'))),
            'commit_box' => new \Twig_Function_Method($this, 'commitBox', array('is_safe' => array('html'))),
            'code_table' => new \Twig_Function_Method($this, 'codeTable', array('is_safe' => array('html'))),
            'user_login' => new \Twig_Function_Method($this, 'userLogin', array('is_safe' => array('html'))),
            'order_github_pagination_links' => new \Twig_Function_Method($this, 'orderGithubPaginationLinks', array('is_safe' => array('all')))
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
        try {
            $output = $this->container->get('templating')->render('CypressGitElephantHostBundle:Twig:output_content.html.twig', array(
                'output' => $this->container->get('cypress.git_elephant_host.git_content')->outputContent($treeObject)
            ));
        } catch (\Exception $e) {
            $output = $this->container->get('templating')->render('CypressGitElephantHostBundle:Twig:output_content.html.twig', array(
                'link' => '(TODO) link to the file'
            ));
        }

        return $output;
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
     * check if the blob is "pygmentable"
     *
     * @param \GitElephant\Objects\TreeObject $blob
     *
     * @throws \InvalidArgumentException
     */
    public function isPygmentableText(TreeObject $blob)
    {
        if (TreeObject::TYPE_BLOB !== $blob->getType()) {
            throw new \InvalidArgumentException('to check the pygmentize option you must pass a TreeObject');
        }
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
        return $this->container->get('templating')->render('CypressGitElephantHostBundle:Twig:commit_box.html.twig', array(
            'slug' => $this->container->get('cypress.git_elephant_host.git_router')->getSlug(),
            'commit' => $commit,
            'link' => $link
        ));
    }

    /**
     * output user login box
     *
     * @return mixed
     */
    public function userLogin()
    {
        return $this->container->get('templating')->render('CypressGitElephantHostBundle:Twig:user_login.html.twig', array(
            'user' => $this->container->get('cypress.git_elephant_host.github.user')
        ));
    }

    /**
     * output code table
     *
     * @param \GitElephant\Objects\Diff\DiffChunk $diffChunk diffChunk object
     *
     * @return mixed
     */
    public function codeTable(DiffChunk $diffChunk)
    {
        return $this->container->get('templating')->render('CypressGitElephantHostBundle:Twig:code_table.html.twig', array(
            'lines' => $diffChunk->getLines()
        ));
    }

    /**
     * @param array $links
     * @return array
     */
    public function orderGithubPaginationLinks(array $links)
    {
        $fields = array('first', 'prev', 'next', 'last');
        foreach ($fields as $field) {
            if (!isset($links[$field])) {
                $links[$field] = null;
            }
        }
        uksort($links, function($a, $b) {
            if ('first' == $a) {
                return -1;
            }
            if ('first' == $b) {
                return 1;
            }
            if ('last' == $a) {
                return 1;
            }
            if ('last' == $b) {
                return -1;
            }

            return 'next' === $a ? 1 : -1;
        });

        return $links;
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
