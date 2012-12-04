<?php
/**
 * User: matteo
 * Date: 25/11/12
 * Time: 22.22
 *
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cypress\GitElephantHostBundle\Controller\Base\Controller as BaseController;

/**
 * Repository controller
 */
class RepositoryController extends BaseController
{
    /**
     * Repository home
     *
     * @param string $slug slug
     *
     * @Route("/repo/{slug}", name="repository")
     * @Template()
     *
     * @return array
     */
    public function repositoryAction($slug)
    {
        $git = $this->getRepositoryRepo()->findOneBySlug($slug)->getGit();
        $ref = 'master';
        $path = '';

        return compact('slug', 'ref', 'path');
    }

    /**
     * Tree Object
     *
     * @param string $slug repo slug
     * @param string $ref  actual reference
     * @param string $path actual path
     *
     * @Route("/repo/{slug}/tree/{ref}/{path}", name="tree_object", requirements={"path" = ".+"})
     * @Template("CypressGitElephantHostBundle:Repository:repository.html.twig")
     *
     * @return array
     */
    public function treeObjectAction($slug, $ref, $path)
    {
        return compact('slug', 'ref', 'path');
    }

    /**
     * @param string $slug repository slug
     * @param string $ref  reference
     * @param string $path path
     *
     * @Template("CypressGitElephantHostBundle:Repository:tree.html.twig")
     * @Route("/repo/{slug}/ajax/{ref}/{path}", name="ajax_tree_object",
     *   requirements={"path" = ".+"},
     *   options={"expose"=true},
     *   defaults={"ref"="master", "path"=""}
     * )
     *
     * @return array
     */
    public function treeAction($slug, $ref, $path)
    {
        //sleep(2);
        $git = $this->getRepositoryRepo()->findOneBySlug($slug)->getGit();
        $tree = $git->getTree($ref, $path);

        return compact('git', 'tree', 'ref', 'path');
    }
}
