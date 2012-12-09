<?php
/**
 * User: matteo
 * Date: 24/11/12
 * Time: 23.25
 *
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Listener;

//use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;
//use Cypress\GitElephantHostBundle\Document\Repository;
use Cypress\GitElephantHostBundle\Entity\Repository;
use GitElephant\Repository as Repo;
use Symfony\Component\Filesystem\Filesystem;
use GitElephant\Repository as Git;
use JMS\DiExtraBundle\Annotation\DoctrineListener;

/**
 * doctrine listener to bind the gitelephant repository class to the document
 *
 * @DoctrineListener(
 *     events = {"postLoad"},
 *     connection = "default",
 *     lazy = true,
 *     priority = 0
 * )
 */
class RepositoryBinderListener
{
    /**
     * postLoad event
     *
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $document = $args->getEntity();
        if ($document instanceof Repository) {
            if (null !== $document->getPath()) {
                $document->setGit(new Repo($document->getPath()));
            }
        }
    }


}
