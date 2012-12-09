<?php
/**
 * User: matteo
 * Date: 09/12/12
 * Time: 13.20
 * 
 * Just for fun...
 */


namespace Cypress\GitElephantHostBundle\Listener;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Cypress\GitElephantHostBundle\Document\Repository;
use GitElephant\Repository as Repo;
use Symfony\Component\Filesystem\Filesystem;
use GitElephant\Repository as Git;
use GitElephant\GitBinary;

/**
 * doctrine listener to clone the repository
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
     */
    public function __construct($kernelRootDir, GitBinary $binary)
    {
        $this->kernelRootDir = $kernelRootDir;
        $this->gitBinary = $binary;
    }

    /**
     * prePersist event
     *
     * @param \Doctrine\ODM\MongoDB\Event\LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $document = $args->getDocument();
        if ($document instanceof Repository) {
            if (null !== $document->getGitUrl() && null === $document->getPath()) {
                $this->initRepository($document);
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
     * @param \Cypress\GitElephantHostBundle\Document\Repository $repository repository document
     */
    private function initRepository(Repository $repository)
    {
        $dirName = sprintf('%s_%s', substr(sha1(uniqid()), 0, 8), $repository->getSlug());
        $path = $this->getRepositoriesDir().'/'.$dirName;
        $fs = new Filesystem();
        $fs->mkdir($path);
        $repository->setPath($path);
        $git = new Git($path, $this->gitBinary);
        //$git->init();
        $git->cloneFrom($repository->getGitUrl());
    }
}
