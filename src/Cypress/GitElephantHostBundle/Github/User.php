<?php
/**
 * User: matteo
 * Date: 28/12/12
 * Time: 23.05
 * 
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Github;

use Buzz\Message\Response;
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

    /**
     * @return Response
     */
    public function getRepositories()
    {
        $response = $this->call('user_repositories_url', false, array('user' => $this->getUsername()));
        $headers = $response->getHeaders();
        unset($headers[0]);
        $outputHeaders = array();
        foreach ($headers as $stringHeader) {
            $pos = strpos($stringHeader, ':');
            $name = substr($stringHeader, 0, $pos);
            $value = trim(substr($stringHeader, $pos+1));
            $outputHeaders[$name] = $value;
        }

        return new \Symfony\Component\HttpFoundation\Response($response->getContent(), 200, $outputHeaders);
    }
}
