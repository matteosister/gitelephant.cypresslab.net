<?php
/**
 * User: matteo
 * Date: 28/12/12
 * Time: 23.04
 * 
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Github;

use Buzz\Client\Curl;
use Buzz\Browser;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * github api
 */
class Api
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
    * @var \Cypress\GitElephantHostBundle\Entity\User
    */
    protected $user;

    /**
     * constructor
     *
     * @param \Doctrine\ORM\EntityManager $em      entity manager
     * @param Request                     $request request
     */
    public function __construct(EntityManager $em, Request $request)
    {
        $this->em = $em;
        $this->user = $this->getUserRepository()->findOneBy(array('id' => $request->cookies->get('user')));
    }

    /**
     * get a value from github, or from the db
     *
     * @param string $what   key
     * @param bool   $cache  cache or not
     * @param array  $params params
     *
     * @return null
     */
    protected function get($what, $cache = true, $params = array())
    {
        $checkUpdate = new \DateTime();
        $checkUpdate->sub(new \DateInterval('P1D'));
        if ($this->user->getGithubDataRefresh() > $checkUpdate && $cache && null !== $val = $this->getUserGithubData($what)) {
            return $val;
        }
        $paths = explode('.', $what);
        $url = null;
        foreach ($paths as $path) {
            $resource = $this->getResource($url, $params);
            $url = $resource->$path;
        }
        $this->user->addGithubData($what, $url);
        $this->em->persist($this->user);
        $this->em->flush();

        return $url;
    }

    protected function call($what, $cache = true, $params = array())
    {
        $url = $this->get($what, false, $params);

        return $this->getResource($url, $params, true);
    }


    private function getUserGithubData($what)
    {
        return isset($this->user->getGithubData()[$what]) ? $this->user->getGithubData()[$what] : null;
    }

    /**
     * @param null  $url    url
     * @param array $params params
     * @param bool  $raw    raw content
     *
     * @return mixed
     */
    private function getResource($url = null, $params = array(), $raw = false)
    {
        $url = 'https://api.github.com'.$url;
        foreach ($params as $key => $value) {
            $url = preg_replace(sprintf('/{%s}/', $key), $value, $url);
        }
        $url = preg_replace('/\{\?.*\}/', '', $url);
        $response = $this->issueRequest($url, $raw);

        return $response;
    }

    /**
     * @param string $url url to call
     * @param bool   $raw raw content
     *
     * @return mixed
     */
    private function issueRequest($url, $raw = false)
    {
        $query = sprintf('?access_token=%s', $this->user->getAccessToken());
        $browser = new Browser(new Curl());
        $response = $browser->get($url.$query);

        return $raw ? $response : json_decode($response->getContent());
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
}
