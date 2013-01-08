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
    /**
     * repo pubbliche
     *
     * @return array
     */
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

    /**
     * attive per utente
     *
     * @param \Cypress\GitElephantHostBundle\Entity\User $user
     *
     * @return array
     */
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

    /**
     * repo attive che possono essere "usate"
     *
     * @return array
     */
    public function getActive()
    {
        $dql = '
            SELECT r FROM Cypress\GitElephantHostBundle\Entity\Repository r
            WHERE r.imported = :imported AND r.path IS NOT NULL
        ';
        $query = $this->_em->createQuery($dql);
        $query->setParameter('imported', true);

        return $query->getResult();
    }
}
