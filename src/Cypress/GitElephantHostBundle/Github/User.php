<?php
/**
 * User: matteo
 * Date: 28/12/12
 * Time: 23.05
 * 
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Github;

use Cypress\GitElephantHostBundle\Github\Api;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * github user
 */
class User extends Api
{
    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        return null !== $this->user;
    }

    /**
     * @return null|string
     */
    public function getAvatarId()
    {
        return $this->get('current_user_url.gravatar_id');
    }

    /**
     * @return null|string
     */
    public function getUsername()
    {
        return $this->get('current_user_url.login');
    }
}
