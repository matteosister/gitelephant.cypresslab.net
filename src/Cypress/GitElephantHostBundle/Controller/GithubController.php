<?php
/**
 * User: matteo
 * Date: 28/12/12
 * Time: 9.21
 * 
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Controller;

use Cypress\GitElephantHostBundle\Controller\Base\Controller as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * controller for github
 *
 * @Route("/github")
 */
class GithubController extends BaseController
{
    /**
     * main page
     *
     * @Route("/", name="github_index")
     */
    public function indexAction()
    {
        return new Response('pippo');
    }
}
