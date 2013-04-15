<?php
/**
 * User: matteo
 * Date: 15/04/13
 * Time: 22.01
 * Just for fun...
 */


namespace Cypress\GitElephantHostBundle\Jobs;


use BCC\ResqueBundle\ContainerAwareJob;

/**
 * Class ImportRepo
 *
 * @package Cypress\GitElephantHostBundle\Jobs
 */
class ImportRepo extends ContainerAwareJob
{
    /**
     * run
     *
     * @param array $args args
     */
    public function run($args)
    {
        var_dump($args);
    }
}