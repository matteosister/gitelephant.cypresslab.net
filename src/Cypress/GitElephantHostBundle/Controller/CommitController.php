<?php
/**
 * User: matteo
 * Date: 07/12/12
 * Time: 10.30
 *
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cypress\GitElephantHostBundle\Controller\Base\Controller as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use GitElephant\Objects\TreeObject;

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
     * commits info
     *
     * @param \Symfony\Component\HttpFoundation\Request $request request
     * @param string                                    $slug    repository slug
     * @param string                                    $ref     reference
     *
     * @internal param string $slug slug
     *
     * @Route("/{slug}/ajax/{ref}/commits.{_format}", name="commits_info",
     *   requirements={"_method"="post", "ref" = ".+"},
     *   options={"expose"=true},
     *   defaults={"ref"="master", "_format"="json"}
     * )
     *
     * @return array
     */
    public function commitInfoAction(Request $request, $slug, $ref)
    {
        //sleep(10);
        $output = array();
        $git = $this->getGit($slug);
        $data = json_decode($request->getContent());
        foreach ($data as $i => $commit) {
            $log = $git->getLog($ref, $commit->path, 1);
            $lastCommit = $log[0];
            $output[$i]['sha'] = $lastCommit->getSha();
            $output[$i]['path'] = $commit->path;
            $output[$i]['message'] = $lastCommit->getMessage()->toString();
            $output[$i]['url'] = $this->generateUrl('commit', array('slug' => $slug, 'sha' => $lastCommit->getSha()));
        }
        $r = new Response(json_encode($output));

        return $r;
    }
}
