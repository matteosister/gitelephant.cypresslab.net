<?php
/**
 * User: matteo
 * Date: 03/01/13
 * Time: 23.21
 * 
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Entity\Repository;

use Cypress\GitElephantHostBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * repository of Repository entities
 */
class RepositoryRepository extends EntityRepository
{
    public function getPublics()
    {
        $dql = '
            SELECT r FROM Cypress\GitElephantHostBundle\Entity\Repository r
            WHERE r.imported = :imported AND r.user IS NULL
        ';
        $query = $this->_em->createQuery($dql);
        $query->setParameter('imported', true);

        return $query->getResult();
    }

    public function getImportedForUser(User $user = null)
    {
        if (null === $user) {
            return array();
        }
        $dql = '
            SELECT r FROM Cypress\GitElephantHostBundle\Entity\Repository r
            WHERE r.imported = :imported AND r.user = :user
        ';
        $query = $this->_em->createQuery($dql);
        $query->setParameter('imported', true);
        $query->setParameter('user', $user);

        return $query->getResult();
    }
}
