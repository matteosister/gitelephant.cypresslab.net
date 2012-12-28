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

/**
 * github api
 */
class Api
{
    /**
     * @var \Cypress\GitElephantHostBundle\Entity\User
     */
    protected $user;

    protected function get($what)
    {
        $paths = explode('.', $what);
        $url = null;
        foreach ($paths as $path) {
            $resource = $this->getResource($url);
            //var_dump($resource->$path);
            $url = $resource->$path;
        }

        return $url;
    }

    private function getResource($url = null)
    {
        $url = 'https://api.github.com'.$url;
        $response = $this->issueRequest($url);

        return $response;
    }

    /**
     * issue request
     */
    private function issueRequest($url)
    {
        $query = sprintf('?access_token=%s', $this->user->getAccessToken());
        $browser = new Browser(new Curl());
        $response = $browser->get($url.$query);

        return json_decode($response->getContent());
    }
}
