<?php

namespace Cypress\GitElephantHostBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Cypress\GitElephantHostBundle\Entity\Repository
 *
 * @MongoDB\Document
 */
class Repository
{
    /**
     * @var integer
     * @MongoDB\Id
     */
    private $id;

    /**
     * @var string
     * @MongoDB\String
     */
    private $name;

    /**
     * @var string
     * @MongoDB\String
     */
    private $path;

    /**
     * @var string
     * @MongoDB\String
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @var \GitElephant\Repository
     */
    private $git;

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
