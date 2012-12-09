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
use Cypress\GitElephantHostBundle\Form\RepositoryType;
use Cypress\GitElephantHostBundle\Document\Repository;

/**
 * Repository controller
 */
class RepositoryController extends BaseController
{
    /**
     * New Repository
     *
     * @Route("/new", name="new_repository")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        $form = $this->createForm('repository', new Repository());
        $formView = $form->createView();

        return compact('formView');
    }

    /**
     * Repository home
     *
     * @param string $slug slug
     *
     * @Route("/{slug}", name="repository")
     * @Template()
     *
     * @return array
     */
    public function repositoryAction($slug)
    {
        $repository = $this->getRepositoryRepo()->findOneBySlug($slug);
        $ref = 'master';
        $path = '';

        return compact('repository', 'slug', 'ref', 'path');
    }

    /**
     * Tree Object
     *
     * @param string $slug repo slug
     * @param string $ref  actual reference
     * @param string $path actual path
     *
     * @Route("/{slug}/tree/{ref}/{path}", name="tree_object", requirements={"path" = ".+"})
     * @Template("CypressGitElephantHostBundle:Repository:repository.html.twig")
     *
     * @return array
     */
    public function treeObjectAction($slug, $ref, $path)
    {
        $repository = $this->getRepositoryRepo()->findOneBySlug($slug);

        return compact('repository', 'slug', 'ref', 'path');
    }

    /**
     * @param string $slug repository slug
     * @param string $ref  reference
     * @param string $path path
     *
     * @Template("CypressGitElephantHostBundle:Repository:tree.html.twig")
     * @Route("/{slug}/ajax/tree/{ref}/{path}", name="ajax_tree_object",
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

    /**
     * @param string $slug repository slug
     * @param string $ref  reference
     * @param string $path path
     *
     * @Template("CypressGitElephantHostBundle:Repository:breadcrumb.html.twig")
     * @Route("/{slug}/ajax/breadcrumb/{ref}/{path}", name="ajax_breadcrumb",
     *   requirements={"path" = ".+"},
     *   options={"expose"=true},
     *   defaults={"ref"="master", "path"=""}
     * )
     *
     * @return array
     */
    public function breadcrumbAction($slug, $ref, $path)
    {
        $repository = $this->getRepositoryRepo()->findOneBySlug($slug);

        return compact('repository', 'ref', 'path');
    }
}
