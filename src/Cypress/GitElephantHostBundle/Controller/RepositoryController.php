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

        return compact('git');
    }
}
