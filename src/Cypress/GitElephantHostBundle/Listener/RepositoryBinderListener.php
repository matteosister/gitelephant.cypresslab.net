<?php
/**
 * User: matteo
 * Date: 24/11/12
 * Time: 23.25
 *
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Listener;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Cypress\GitElephantHostBundle\Document\Repository;
use GitElephant\Repository as Repo;

class RepositoryBinderListener
{
    public function postLoad(LifecycleEventArgs $args)
    {
        $document = $args->getDocument();
        $documentManager = $args->getDocumentManager();
        if ($document instanceof Repository) {
            $document->setGit(new Repo($document->getPath()));
        }
    }
}
