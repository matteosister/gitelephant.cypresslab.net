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
use Cypress\GitElephantHostBundle\Entity\Repository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Repository controller
 */
class RepositoryController extends BaseController
{
    /**
     * New Repository
     *
     * @param \Symfony\Component\HttpFoundation\Request $request request
     *
     * @Route("/new", name="new_repository")
     * @Template()
     *
     * @return array
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm('repository', new Repository());
        $formView = $form->createView();
        if ($request->isMethod('post')) {
            $form->bind($request);
            if ($form->isValid()) {
                $repository = $form->getData();
                $this->getEM()->persist($repository);
                $this->getEM()->flush();
            }
        }

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
        if (null === $repository->getGit()->getBranchOrTag($ref)) {
            $ref = $repository->getGit()->getMainBranch()->getName();

            return new RedirectResponse($this->generateUrl('tree_object', array('slug' => $slug, 'ref' => $ref)));
        }
        $path = '';

        return compact('repository', 'ref', 'path');
    }

    /**
     * Tree Object
     *
     * @param string $slug repo slug
     * @param string $ref  actual reference
     * @param string $path actual path
     *
     * @Route("/{slug}/tree/{ref}/{path}", name="tree_object",
     *   requirements={"ref" = ".+", "path" = ".+"}, defaults={"path" = ""})
     * @Template("CypressGitElephantHostBundle:Repository:repository.html.twig")
     *
     * @return array
     */
    public function treeObjectAction($slug, $ref, $path)
    {
        $repository = $this->getRepositoryRepo()->findOneBySlug($slug);
        $parts = $this->get('ref_path.splitter')->split($repository->getGit(), $ref, $path);
        $ref = $parts[0];
        $path = $parts[1];

        return compact('repository', 'ref', 'path');
    }

    /**
     * tree action
     *
     * @param string $slug repository slug
     * @param string $ref  reference
     * @param string $path path
     *
     * @Template("CypressGitElephantHostBundle:Repository:tree.html.twig")
     * @Route("/{slug}/ajax/tree/{ref}/{path}", name="ajax_tree_object",
     *   requirements={"ref" = ".+", "path" = ".+"},
     *   options={"expose"=true},
     *   defaults={"ref"="master", "path"=""}
     * )
     *
     * @return array
     */
    public function treeAction($slug, $ref, $path)
    {
        $git = $this->getRepositoryRepo()->findOneBy(array('slug' => $slug))->getGit();
        $parts = $this->get('ref_path.splitter')->split($git, $ref, $path);
        $ref = $parts[0];
        $path = $parts[1];
        $tree = $git->getTree($ref, $path);

        return compact('git', 'tree', 'ref', 'path', 'slug');
    }

    /**
     * binary tree action
     *
     * @param string $slug repository slug
     * @param string $ref  reference
     * @param string $path path
     *
     * @Template("CypressGitElephantHostBundle:Repository:tree.html.twig")
     * @Route("/{slug}/ajax/binary-tree/{ref}/{path}", name="ajax_binary_tree_object",
     *   requirements={"ref" = ".+", "path" = ".+"},
     *   options={"expose"=true},
     *   defaults={"ref"="master", "path"=""}
     * )
     *
     * @return Response
     */
    public function binaryTreeAction($slug, $ref, $path)
    {
        $git = $this->getRepositoryRepo()->findOneBy(array('slug' => $slug))->getGit();
        $parts = $this->get('ref_path.splitter')->split($git, $ref, $path);
        $ref = $parts[0];
        $path = $parts[1];
        $tree = $git->getTree($ref, $path);
        $response = new Response();
        $response->setContent($tree->getBinaryData());

        return $response;
    }

    /**
     * breadcrumb render
     *
     * @param string $slug repository slug
     * @param string $ref  reference
     * @param string $path path
     *
     * @Template("CypressGitElephantHostBundle:Repository:breadcrumb.html.twig")
     * @Route("/{slug}/ajax/breadcrumb/{ref}/{path}", name="ajax_breadcrumb",
     *   requirements={"ref" = ".+", "path" = ".+"},
     *   options={"expose"=true},
     *   defaults={"ref"="master", "path"=""}
     * )
     *
     * @return array
     */
    public function breadcrumbAction($slug, $ref, $path)
    {
        $repository = $this->getRepositoryRepo()->findOneBySlug($slug);
        $parts = $this->get('ref_path.splitter')->split($repository->getGit(), $ref, $path);
        $ref = $parts[0];
        $path = $parts[1];

        return compact('repository', 'ref', 'path');
    }
}
