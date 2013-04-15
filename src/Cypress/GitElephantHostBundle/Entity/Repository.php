<?php

namespace Cypress\GitElephantHostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\SerializerBundle\Annotation\ExclusionPolicy;
use JMS\SerializerBundle\Annotation\Expose;

/**
 * Cypress\GitElephantHostBundle\Entity\Repository
 *
 * @ORM\Entity(repositoryClass="Cypress\GitElephantHostBundle\Entity\Repository\RepositoryRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="repositories")
 * @ExclusionPolicy("all")
 */
class Repository
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
     * @Expose
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     * @Expose
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     * @Expose
     */
    private $gitUrl;

    /**
     * @var string
     *
     * @ORM\Column
     * @Gedmo\Slug(fields={"name"})
     * @Expose
     */
    private $slug;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     * @Expose
     */
    private $imported;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Cypress\GitElephantHostBundle\Entity\User", inversedBy="repositories")
     */
    private $user;

    /**
     * @var \GitElephant\Repository
     */
    private $git;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", name="default_repository")
     */
    private $default;

    /**
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var datetime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->imported = false;
        $this->default = false;
    }

    /**
     * toString
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name repository name
     *
     * @return Repository
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set Path
     *
     * @param string $path the path variable
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Get Path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set GitUrl
     *
     * @param string $gitUrl the gitUrl variable
     */
    public function setGitUrl($gitUrl)
    {
        $this->gitUrl = $gitUrl;
    }

    /**
     * Get GitUrl
     *
     * @return string
     */
    public function getGitUrl()
    {
        return $this->gitUrl;
    }

    /**
     * Set Slug
     *
     * @param string $slug the slug variable
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get Slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Imported setter
     *
     * @param boolean $imported the imported variable
     */
    public function setImported($imported)
    {
        $this->imported = $imported;
    }

    /**
     * Imported getter
     *
     * @return boolean
     */
    public function getImported()
    {
        return $this->imported;
    }

    /**
     * Set User
     *
     * @param \Cypress\GitElephantHostBundle\Entity\User $user the user variable
     */
    public function setUser($user)
    {
        $this->user = $user;
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

    /**
     * Set Git
     *
     * @param \GitElephant\Repository $git the git variable
     */
    public function setGit($git)
    {
        $this->git = $git;
    }

    /**
     * Get Git
     *
     * @return \GitElephant\Repository
     */
    public function getGit()
    {
        return $this->git;
    }

    /**
     * Set Created
     *
     * @param \Cypress\GitElephantHostBundle\Entity\datetime $created the created variable
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * Get Created
     *
     * @return \Cypress\GitElephantHostBundle\Entity\datetime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set Updated
     *
     * @param \Cypress\GitElephantHostBundle\Entity\datetime $updated the updated variable
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * Get Updated
     *
     * @return \Cypress\GitElephantHostBundle\Entity\datetime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set Default
     *
     * @param boolean $default the default variable
     */
    public function setDefault($default)
    {
        $this->default = $default;
    }

    /**
     * Get Default
     *
     * @return boolean
     */
    public function getDefault()
    {
        return $this->default;
    }
}
