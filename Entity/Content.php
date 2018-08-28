<?php

namespace Builder\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Sonata\UserBundle\Entity\Group;
use Builder\PageBundle\Entity\Page_Content;

/**
 * Content
 *
 * @ORM\Table(name="app_content")
 * @ORM\Entity(repositoryClass="Builder\PageBundle\Repository\ContentRepository")
 */
class Content
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity="Application\Sonata\UserBundle\Entity\Group", inversedBy="contents")
     * @ORM\JoinTable(name="app_contents_rights")
     */
    private $rights;

    /** @ORM\OneToMany(targetEntity="Builder\PageBundle\Entity\Page_Content", mappedBy="content", orphanRemoval=true) */
    private $pageContents;

    /**
     * @var bool
     *
     * @ORM\Column(name="locked", type="boolean")
     */
    private $locked = false;
    
    /**
     * @var string
     *
     * @ORM\Column(name="class", type="string", length=255, nullable=true)
     */
    private $class = "";

    public function __construct()
    {
        $this->rights = new ArrayCollection();
        $this->pageContents = new ArrayCollection(); 
        $this->class = "";
    }

        
    /**
     * @param Group $group
     */
    public function addRights(Group $group)
    {
        $this->rights[] = $group;
    }

    /**
     * @param Group $group
     */
    public function removeRights(Group $group)
    {
        $this->rights->removeElement($group);
    }

    /**
     * @return mixed
     */
    public function getRights()
    {
        return $this->rights;
    }

    /**
     * Set list rights
     *
     * @param string $rights
     *
     * @return Content
     */
    public function setRights($rights)
    {
        $this->rights = [];
        foreach ($rights as $i) {
            $this->addRights($i);
        }

        return $this;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Content
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Content
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Content
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set locked
     *
     * @param boolean $locked
     *
     * @return Content
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * Get locked
     *
     * @return bool
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * Set class
     *
     * @param string $class
     *
     * @return Page_Content
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }
}

