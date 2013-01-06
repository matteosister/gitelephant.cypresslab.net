<?php
/**
 * User: matteo
 * Date: 28/12/12
 * Time: 9.38
 * 
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * A user entity
 *
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="access_token", nullable=true)
     */
    private $accessToken;

    /**
     * @var array
     *
     * @ORM\Column(name="github_data", type="array", nullable=true)
     */
    private $githubData;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="github_data_refresh", type="datetime")
     */
    private $githubDataRefresh;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Cypress\GitElephantHostBundle\Entity\Repository", mappedBy="user")
     */
    private $repositories;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updated;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->token = sha1(uniqid());
        $this->githubData = array();
        $this->githubDataRefresh = new \DateTime();
        $this->repositories = new ArrayCollection();
    }

    /**
     * Id getter
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Username setter
     *
     * @param string $username the username variable
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Username getter
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Token setter
     *
     * @param string $token the token variable
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * Token getter
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * AccessToken setter
     *
     * @param string $accessToken the accessToken variable
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * AccessToken getter
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set GithubData
     *
     * @param array $githubData the githubData variable
     */
    public function setGithubData($githubData)
    {
        $this->githubData = $githubData;
        $this->githubDataRefresh = new \DateTime();
    }

    /**
     * Get GithubData
     *
     * @return array
     */
    public function getGithubData()
    {
        return $this->githubData;
    }

    /**
     * add data to github
     *
     * @param string $key   api path
     * @param string $value returned value
     *
     * @return void
     */
    public function addGithubData($key, $value)
    {
        $this->githubData[$key] = $value;
        $this->githubDataRefresh = new \DateTime();
    }

    /**
     * Set GithubDataRefresh
     *
     * @param \DateTime $githubDataRefresh the githubDataRefresh variable
     */
    public function setGithubDataRefresh($githubDataRefresh)
    {
        $this->githubDataRefresh = $githubDataRefresh;
    }

    /**
     * Get GithubDataRefresh
     *
     * @return \DateTime
     */
    public function getGithubDataRefresh()
    {
        return $this->githubDataRefresh;
    }

    /**
     * Set Repositories
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $repositories the repositories variable
     */
    public function setRepositories($repositories)
    {
        $this->repositories = $repositories;
    }

    /**
     * Get Repositories
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getRepositories()
    {
        return $this->repositories;
    }

    /**
     * Created setter
     *
     * @param \DateTime $created the created variable
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * Created getter
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Updated setter
     *
     * @param \DateTime $updated the updated variable
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * Updated getter
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }
}