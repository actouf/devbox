<?php

namespace Disko\ExampleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Disko\CoreBundle\Traits\GaufretteTrait as GaufretteTrait;

/**
 * @ORM\Entity(repositoryClass="Disko\ExampleBundle\Repository\ExampleRepository")
 * @ORM\Table(name="dk_example")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Example
{
    use GaufretteTrait;

    /**
     * Id
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Name
     *
     * @ORM\Column(length=255, nullable=true)
     */
    protected $name;

    /**
     * Slug
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=255, unique=true)
     */
    protected $slug;

    /**
     * Description
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $active = false;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $pathKeyword;

    protected $fileKeyword;

    protected $fileAtDeleteKeyword = false;

    /**
     * @ORM\Column(name="deletedAt", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * Creation
     *
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * Edit
     *
     * @var datetime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updated;


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
     * @param string $name
     * @return Example
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
     * Set slug
     *
     * @param string $slug
     * @return Example
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Example
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Example
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set pathKeyword
     *
     * @param string $pathKeyword
     * @return Example
     */
    public function setPathKeyword($pathKeyword)
    {
        $this->pathKeyword = $pathKeyword;

        return $this;
    }

    /**
     * Get pathKeyword
     *
     * @return string 
     */
    public function getPathKeyword()
    {
        return $this->pathKeyword;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * @return Example
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime 
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @return mixed
     */
    public function getFileKeyword()
    {
        return $this->fileKeyword;
    }

    /**
     * @param mixed $fileKeyword
     */
    public function setFileKeyword($fileKeyword)
    {
        $this->fileKeyword = $fileKeyword;
    }

    /**
     * @return boolean
     */
    public function isFileAtDeleteKeyword()
    {
        return $this->fileAtDeleteKeyword;
    }

    /**
     * @param boolean $fileAtDeleteKeyword
     */
    public function setFileAtDeleteKeyword($fileAtDeleteKeyword)
    {
        $this->fileAtDeleteKeyword = $fileAtDeleteKeyword;
    }

    /**
     * @return datetime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param datetime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return datetime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param datetime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }
}
