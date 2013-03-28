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
use Symfony\Component\HttpFoundation\StreamedResponse;

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
        if (null === $this->getUser()) {
            return new RedirectResponse($this->generateUrl('homepage'));
        }
        $repository = new Repository();
        //$repository->setUser($this->getUser());
        $form = $this->createForm('repository', $repository);
        $formView = $form->createView();
        if ($request->isMethod('post')) {
            $form->bind($request);
            if ($form->isValid()) {
                $repository = $form->getData();
                $this->getEM()->persist($repository);
                $this->getEM()->flush();
                $this->getSession()->getFlashBag()->add('repository', 'The repository is being imported right now...hold on...');

                return new RedirectResponse($this->generateUrl('clone_repository', array('slug' => $repository->getSlug())));
            }
        }

        return compact('formView');
    }

    /**
     * Clone Repository
     *
     * @param string $slug repository id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/clone/{slug}", name="clone_repository")
     */
    public function cloneAction($slug)
    {
        $repository = $this->getRepository($slug);
        $this->getCloner()->initRepository($repository);

        return new RedirectResponse($this->generateUrl('homepage'));
    }

    /**
     * Repository home
     *
     * @param string $slug slug
     *
     * @Route("/{slug}", name="repository", options={"expose"=true})
     *
     * @return array
     */
    public function repositoryAction($slug)
    {
        $response = new Response();
        if ($response->isNotModified($this->getRequest())) {
            return $response;
        }
        $repository = $this->getRepository($slug);
        $ref = 'master';
        if (null === $repository->getGit()->getBranchOrTag($ref)) {
            $ref = $repository->getGit()->getMainBranch()->getName();

            return new RedirectResponse($this->generateUrl('tree_object', array('slug' => $slug, 'ref' => $ref)));
        }
        $path = null;
        $response = $this->render('CypressGitElephantHostBundle:Repository:repository.html.twig',
            compact('repository', 'ref', 'path'));
        $response->setMaxAge(60*60);
        $response->setSharedMaxAge(60*60);

        return $response;
    }

    /**
     * Tree Object
     *
     * @param string $slug repo slug
     * @param string $ref  actual reference
     * @param string $path actual path
     *
     * @Route("/{slug}/tree/{ref}/{path}", name="tree_object",
     *   requirements={"ref" = "\S+", "path" = ".+"}, defaults={"path" = ""})
     * @Template("CypressGitElephantHostBundle:Repository:repository.html.twig")
     *
     * @return array
     */
    public function treeObjectAction($slug, $ref, $path)
    {
        $response = new Response();
        if ($response->isNotModified($this->getRequest())) {
            return $response;
        }
        $repository = $this->getRepository($slug);
        $parts = $this->getRefPathSplitter()->split($repository->getGit(), $ref, $path);
        $ref = $parts[0];
        $path = $parts[1];
        $response = $this->render('CypressGitElephantHostBundle:Repository:repository.html.twig',
            compact('repository', 'ref', 'path'));
        $response->setMaxAge(60*60);
        $response->setSharedMaxAge(60*60);

        return $response;
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
     *   requirements={"ref" = "\S+", "path" = ".+"},
     *   options={"expose"=true},
     *   defaults={"ref"="master", "path"=""}
     * )
     *
     * @return array
     */
    public function treeAction($slug, $ref, $path)
    {
        $git = $this->getGit($slug);
        $parts = $this->getRefPathSplitter()->split($git, $ref, $path);
        $ref = $parts[0];
        $path = $parts[1];
        $tree = $git->getTree($ref, $path);
        try {
            $readme = $git->getTree('master', 'README.md')->getBinaryData();
        } catch (\Exception $e) {
            $readme = null;
        }

        return compact('git', 'tree', 'ref', 'path', 'slug', 'readme');
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
        $git = $this->getGit($slug);
        $parts = $this->getRefPathSplitter()->split($git, $ref, $path);
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
        $repository = $this->getRepository($slug);
        $parts = $this->getRefPathSplitter()->split($repository->getGit(), $ref, $path);
        $ref = $parts[0];
        $path = $parts[1];

        return compact('repository', 'ref', 'path');
    }
}
