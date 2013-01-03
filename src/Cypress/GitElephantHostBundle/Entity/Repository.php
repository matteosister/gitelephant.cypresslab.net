<?php

namespace Cypress\GitElephantHostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Cypress\GitElephantHostBundle\Entity\Repository
 *
 * @ORM\Entity(repositoryClass="Cypress\GitElephantHostBundle\Entity\Repository\RepositoryRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="repositories")
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
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     */
    private $gitUrl;

    /**
     * @var string
     *
     * @ORM\Column
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $imported;

    /**
     * @var \GitElephant\Repository
     */
    private $git;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->imported = false;
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
}
