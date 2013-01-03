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

/**
 * doctrine listener to clone the repository
 *
 * @DoctrineListener(
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
     * @var \GitElephant\GitBinary
     */
    private $gitBinary;

    /**
     * constructor
     *
     * @param string                 $kernelRootDir root dir
     * @param \GitElephant\GitBinary $binary        binary
     *
     * @InjectParams({
     *     "kernelRootDir" = @Inject("%kernel.root_dir%"),
     *     "binary" = @Inject("cypress_git_elephant.git_binary")
     * })
     */
    public function __construct($kernelRootDir, GitBinary $binary)
    {
        $this->kernelRootDir = $kernelRootDir;
        $this->gitBinary = $binary;
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
                $args->getEntityManager()->persist($entity);
                $args->getEntityManager()->flush();
            }
        }
    }

    /**
     * repository dir
     *
     * @return string
     */
    private function getRepositoriesDir()
    {
        return realpath($this->kernelRootDir.'/../repositories');
    }

    /**
     * clone the repo from the git url and set the path
     *
     * @param \Cypress\GitElephantHostBundle\Entity\Repository $repository repository document
     */
    private function initRepository(Repository $repository)
    {
        $dirName = sprintf('%s_%s', substr(sha1(uniqid()), 0, 8), $repository->getSlug());
        $path = $this->getRepositoriesDir().'/'.$dirName;
        $fs = new Filesystem();
        $fs->mkdir($path);
        $repository->setPath($path);
        Git::createFromRemote($repository->getGitUrl(), $path, $this->gitBinary);
    }
}
