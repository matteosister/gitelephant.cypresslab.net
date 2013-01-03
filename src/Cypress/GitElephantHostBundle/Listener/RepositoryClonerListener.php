<?php
/**
 * User: matteo
 * Date: 09/12/12
 * Time: 13.20
 * 
 * Just for fun...
 */


namespace Cypress\GitElephantHostBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
//use Cypress\GitElephantHostBundle\Document\Repository;
use Cypress\GitElephantHostBundle\Entity\Repository;
use GitElephant\Repository as Repo;
use Symfony\Component\Filesystem\Filesystem;
use GitElephant\Repository as Git;
use GitElephant\GitBinary;
use JMS\DiExtraBundle\Annotation\DoctrineListener;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;
use Symfony\Component\Process\Process;

/**
 * doctrine listener to clone the repository
 *
 * DoctrineListener(
 *     events = {"postPersist"},
 *     connection = "default",
 *     lazy = true
 * )
 */
class RepositoryClonerListener
{
    /**
     * @var string
     */
    private $kernelRootDir;

    /**
     * constructor
     *
     * @param string $kernelRootDir root dir
     *
     * @InjectParams({
     *     "kernelRootDir" = @Inject("%kernel.root_dir%")
     * })
     */
    public function __construct($kernelRootDir)
    {
        $this->kernelRootDir = $kernelRootDir;
    }

    /**
     * postPersist event
     *
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Repository) {
            if (null !== $entity->getGitUrl() && null === $entity->getPath()) {
                $this->initRepository($entity);
            }
        }
    }

    /**
     * repository dir
     *
     * @return string
     */
    private function getRootDir()
    {
        return realpath($this->kernelRootDir.'/../');
    }

    /**
     * clone the repo from the git url and set the path
     *
     * @param \Cypress\GitElephantHostBundle\Entity\Repository $repository repository document
     */
    private function initRepository(Repository $repository)
    {
        $cmd = sprintf('nohup ./app/console gitelephant:repository:import %s', $repository->getId());
        var_dump($cmd);
        $process = new Process($cmd, $this->getRootDir());
        $process->run();
        var_dump($process->getOutput());
        var_dump($process->getErrorOutput());
        die;
    }
}
