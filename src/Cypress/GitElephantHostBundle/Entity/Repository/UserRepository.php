<?php
/**
 * User: matteo
 * Date: 07/01/13
 * Time: 11.51
 * 
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Entity\Repository;

use Cypress\GitElephantHostBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * user repository
 */
class UserRepository extends EntityRepository
{
    public function getImportedForUser(User $user)
    {
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
