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

        return compact('git', 'ref', 'path');
    }

    /**
     * Tree Object
     *
     * @Route("/repo/{slug}/tree/{ref}/{path}", name="tree_object", requirements={"path" = ".+"})
     * @Template("CypressGitElephantHostBundle:Repository:repository.html.twig");
     */
    public function treeObjectAction($slug, $ref, $path)
    {
        $git = $this->getRepositoryRepo()->findOneBySlug($slug)->getGit();

        return compact('git', 'ref', 'path');
    }
}
