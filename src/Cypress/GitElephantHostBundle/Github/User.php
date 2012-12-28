<?php
/**
 * User: matteo
 * Date: 28/12/12
 * Time: 23.05
 * 
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Github;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class User extends Api
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * constructor
     *
     * @param \Doctrine\ORM\EntityManager               $em      entity manager
     * @param \Symfony\Component\HttpFoundation\Request $request request
     */
    public function __construct(EntityManager $em, Request $request)
    {
        $this->em = $em;
        $this->user = $this->getUserRepository()->findOneBy(array('id' => $request->cookies->get('user')));
    }

    /**
     * user repository
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    private function getUserRepository()
    {
        return $this->em->getRepository('Cypress\GitElephantHostBundle\Entity\User');
    }

    /**
     * Get User
     *
     * @return \Cypress\GitElephantHostBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function getAvatarId()
    {
        return $this->get('current_user_url.gravatar_id');
    }

    public function getUsername()
    {
        return $this->get('current_user_url.login');
    }
}
