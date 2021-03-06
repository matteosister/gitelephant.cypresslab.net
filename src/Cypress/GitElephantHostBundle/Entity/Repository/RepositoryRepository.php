<?php
/**
 * User: matteo
 * Date: 03/01/13
 * Time: 23.21
 * 
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Entity\Repository;

use Cypress\GitElephantHostBundle\Entity\Repository;
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
     * per utente
     *
     * @param \Cypress\GitElephantHostBundle\Entity\User $user
     *
     * @return array
     */
    public function getForUser(User $user = null)
    {
        if (null === $user) {
            return array();
        }
        $dql = '
            SELECT r FROM Cypress\GitElephantHostBundle\Entity\Repository r
            WHERE r.user = :user
        ';
        $query = $this->_em->createQuery($dql);
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

    /**
     * repo non importate correttamente
     *
     * @param \DateTime $since
     *
     * @return array
     */
    public function getToBeCleaned(\DateTime $since)
    {
        $dql = '
            SELECT r FROM Cypress\GitElephantHostBundle\Entity\Repository r
            WHERE r.imported = :imported
            AND r.default = :default_repo
            AND r.created < :created
        ';
        $query = $this->_em->createQuery($dql);
        $query->setParameter('imported', false);
        $query->setParameter('default_repo', false);
        $query->setParameter('created', $since);

        return $query->getResult();
    }

    /**
     * @return array
     */
    public function getOrdered()
    {
        $dql = '
            SELECT r FROM Cypress\GitElephantHostBundle\Entity\Repository r
            ORDER BY r.created DESC
        ';
        $query = $this->_em->createQuery($dql);

        return $query->getResult();
    }

    /**
     * override
     *
     * @param array $criteria criteria
     * @param array $orderBy  order
     *
     * @return Repository
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        return parent::findOneBy($criteria, $orderBy);
    }
}
