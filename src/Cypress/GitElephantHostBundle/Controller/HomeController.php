<?php

namespace Cypress\GitElephantHostBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Home Controller
 */
class HomeController extends BaseController
{
    /**
     * homepage
     *
     * @Route("/")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        $repositories = $this->getRepositoryRepo()->findAll();

        return compact('repositories');
    }
}