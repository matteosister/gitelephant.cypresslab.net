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
    private $repositoriesDir;

    /**
     * @var \Symfony\Bridge\Monolog\Logger
     */
    private $logger;

    /**
     * constructor
     *
     * @param string                         $repositoriesDir root dir
     * @param \Symfony\Bridge\Monolog\Logger $logger          logger
     *
     * @InjectParams({
     *     "repositoriesDir" = @Inject("%cypress_git_elephant_host.repositories_dir%"),
     *     "logger" = @Inject("logger")
     * })
     */
    public function __construct($repositoriesDir, Logger $logger)
    {
        $this->repositoriesDir = $repositoriesDir;
        $this->logger = $logger;
    }

    /**
     * clone the repo from the git url and set the path
     *
     * @param \Cypress\GitElephantHostBundle\Entity\Repository $repository repository document
     */
    public function initRepository(Repository $repository)
    {
        $cmd = sprintf('nohup ./../app/console gitelephant:repository:import %s > /dev/null 2> /dev/null &', $repository->getId());
        $this->logger->info(sprintf('executing "%s"', $cmd));
        $process = new Process($cmd, $this->repositoriesDir);
        $process->run();
        if (!$process->isSuccessful()) {
            $this->logger->err($process->getErrorOutput());
        }
    }
}
