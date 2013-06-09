<?php
/**
 * User: matteo
 * Date: 07/12/12
 * Time: 10.30
 *
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Controller;

use GitElephant\Objects\Branch;
use GitElephant\Objects\Tree;
use GitElephant\Repository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cypress\GitElephantHostBundle\Controller\Base\Controller as BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * commit controller
 */
class CommitController extends BaseController
{
    /**
     * single commit
     *
     * @param string $slug slug
     * @param string $sha  commit sha
     *
     * @return array
     *
     * @Route("/{slug}/commit/{sha}", name="commit")
     * @Template
     *
     * @return array
     */
    public function commitAction($slug, $sha)
    {
        $repository = $this->getRepository($slug);
        $commit = $repository->getGit()->getCommit($sha);

        return compact('repository', 'commit');
    }

    /**
     * commits
     *
     * @param \Symfony\Component\HttpFoundation\Request $request request
     * @param string                                    $slug    slug
     * @param string                                    $ref     reference
     *
     * @internal param string $slug slug
     *
     * @Route("/{slug}/commits/{ref}.{_format}", name="commits",
     *   requirements={"_method"="get", "ref" = ".+"},
     *   options={"expose"=false},
     *   defaults={"ref"="master", "_format"="html"}
     * )
     * @Template
     *
     * @return array
     */
    public function commitsAction(Request $request, $slug, $ref = 'master')
    {
        $repository = $this->getRepository($slug);

        return compact('repository', 'ref');
    }

    /**
     * roots commits info
     *
     * @param \Symfony\Component\HttpFoundation\Request $request request
     * @param string                                    $slug    repository slug
     * @param string                                    $ref     reference
     *
     * @internal param string $slug slug
     *
     * @Route("/{slug}/ajax/tree/commits/{ref}.{_format}", name="commits_root_info",
     *   requirements={"_method"="get", "ref" = "\S+"},
     *   options={"expose"=true},
     *   defaults={"ref"="master", "_format"="json"}
     * )
     *
     * @return array
     */
    public function commitRootInfoAction(Request $request, $slug, $ref)
    {
        $response = new Response();
        if ($response->isNotModified($request)) {
            return $response;
        }

        $git = $this->getGit($slug);
        $output = $this->getCommitsData($git, $slug, $ref, '');
        $response = new Response(json_encode($output));
        $response->setPublic();
        $response->setMaxAge(60*60);
        $response->setSharedMaxAge(60*60);

        return $response;
    }

    /**
     * commits info
     *
     * @param \Symfony\Component\HttpFoundation\Request $request request
     * @param string                                    $slug    repository slug
     * @param string                                    $ref     reference
     * @param string                                    $path    path
     *
     * @internal param string $slug slug
     *
     * @Route("/{slug}/ajax/tree/commits/{ref}/{path}.{_format}", name="commits_info",
     *   requirements={"_method"="get", "ref" = "\S+", "path" = ".+"},
     *   options={"expose"=true},
     *   defaults={"ref"="master", "_format"="json", "path"=""}
     * )
     *
     * @return array
     */
    public function commitInfoAction(Request $request, $slug, $ref, $path)
    {
        $response = new Response();
        if ($response->isNotModified($request)) {
            return $response;
        }

        $git = $this->getGit($slug);
        $output = $this->getCommitsData($git, $slug, $ref, $path);
        $response = new Response(json_encode($output));
        $response->setPublic();
        $response->setMaxAge(60*60);
        $response->setSharedMaxAge(60*60);

        return $response;
    }

    /**
     * @param Repository $git  Repository instance
     * @param string     $slug git slug
     * @param string     $ref  reference
     * @param string     $path path
     *
     * @return array
     */
    private function getCommitsData(Repository $git, $slug, $ref, $path)
    {
        $parts = $this->getRefPathSplitter()->split($git, $ref, $path);
        $ref = $parts[0];
        $path = $parts[1];
        $branch = Branch::checkout($git, $ref);
        $tree = $git->getTree($branch, $path);
        $output = array();
        foreach ($tree as $i => $node) {
            $log = $git->getLog($branch, $node->getFullPath(), 1);
            $lastCommit = $log[0];
            $output[$i]['author_email'] = $lastCommit->getAuthor()->getEmail();
            $output[$i]['author_name'] = $lastCommit->getAuthor()->getName();
            $output[$i]['sha'] = $lastCommit->getSha();
            $output[$i]['path'] = $node->getFullPath();
            $output[$i]['message'] = $lastCommit->getMessage()->toString();
            $output[$i]['url'] = $this->generateUrl('commit', array('slug' => $slug, 'sha' => $lastCommit->getSha()));
        }

        return $output;
    }
}
