<?php
/**
 * User: matteo
 * Date: 28/12/12
 * Time: 9.38
 * 
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

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
     * @ORM\Column
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="access_token", nullable=true)
     */
    private $accessToken;

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