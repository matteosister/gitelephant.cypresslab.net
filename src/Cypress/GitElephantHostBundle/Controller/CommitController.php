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
     * commits
     *
     * @param string $slug slug
     *
     * @internal param string $slug slug
     *
     * @Route("/{slug}/commits.{_format}", name="commits",
     *   requirements={"_method"="get"},
     *   options={"expose"=false},
     *   defaults={"ref"="master", "_format"="html"}
     * )
     * @Template
     *
     * @return array
     */
    public function commitsAction($slug)
    {
        $repository = $this->getRepositoryRepo()->findOneBy(array('slug' => $slug));
        $commits = $repository->getGit()->getLog();

        return compact('commits');
    }

    /**
     * commits info
     *
     * @param \Symfony\Component\HttpFoundation\Request $request request
     * @param string                                    $slug    repository slug
     *
     * @internal param string $slug slug
     *
     * @Route("/{slug}/ajax/commits.{_format}", name="commits_info",
     *   requirements={"_method"="post"},
     *   options={"expose"=true},
     *   defaults={"ref"="master", "_format"="json"}
     * )
     *
     * @return array
     */
    public function commitInfoAction(Request $request, $slug)
    {
        //sleep(10);
        $output = array();
        $git = $this->getGit($slug);
        $data = json_decode($request->getContent());
        foreach ($data as $i => $commit) {
            $log = $git->getLog('master', $commit->path, 1);
            $lastCommit = $log[0];
            $output[$i]['sha'] = $lastCommit->getSha();
            $output[$i]['path'] = $commit->path;
            $output[$i]['message'] = $lastCommit->getMessage()->toString();
        }
        $r = new Response(json_encode($output));

        return $r;
    }
}
