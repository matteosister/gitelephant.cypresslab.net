<?php

namespace Cypress\GitElephantHostBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cypress\GitElephantHostBundle\Controller\Base\Controller as BaseController;

/**
 * Home Controller
 */
class HomeController extends BaseController
{
    /**
     * homepage
     *
     * @Route("/", name="homepage")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        $repositories = $this->getRepositoryRepo()->findAll();

        return compact('repositories');
    }

    /**
     * @Template()
     *
     * @return array
     */
    public function headerAction()
    {
        $repositories = $this->getRepositoryRepo()->findAll(array('imported' => true));

        return compact('repositories');
    }
}