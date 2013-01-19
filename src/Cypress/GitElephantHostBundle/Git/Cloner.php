<?php
/**
 * User: matteo
 * Date: 04/01/13
 * Time: 9.42
 * 
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Git;

use Cypress\GitElephantHostBundle\Entity\Repository;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Process\Process;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;

/**
 * clone the repository to the filesystem
 *
 * @Service
 */
class Cloner
{
    /**
     * @var string
     */
    private $kernelDir;

    /**
     * @var \Symfony\Bridge\Monolog\Logger
     */
    private $logger;

    /**
     * constructor
     *
     * @param string                         $kernelDir kernel dir
     * @param \Symfony\Bridge\Monolog\Logger $logger    logger
     *
     * @InjectParams({
     *     "kernelDir" = @Inject("%kernel.root_dir%"),
     *     "logger" = @Inject("logger")
     * })
     */
    public function __construct($kernelDir, Logger $logger)
    {
        $this->kernelDir = realpath($kernelDir);
        $this->logger = $logger;
    }

    /**
     * clone the repo from the git url and set the path
     *
     * @param \Cypress\GitElephantHostBundle\Entity\Repository $repository repository document
     */
    public function initRepository(Repository $repository)
    {
        $cmd = sprintf('nohup ./app/console -e=prod gitelephant:repository:import %s > /dev/null 2> /dev/null &', $repository->getId());
        //$cmd = sprintf('./app/console -e=prod gitelephant:repository:import %s', $repository->getId());
        $this->logger->info(sprintf('executing "%s"', $cmd));
        $process = new Process($cmd, realpath($this->kernelDir.'/../'));
        $process->run();
        $this->logger->info($process->getOutput());
        if (!$process->isSuccessful()) {
            $this->logger->err($process->getErrorOutput());
            throw new \RuntimeException($process->getErrorOutput());
        }
    }
}
