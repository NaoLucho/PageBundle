<?php

namespace Builder\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Sonata\UserBundle\Entity\Group;
/**
 * Page
 *
 * @ORM\Table(name="app_page")
 * @ORM\Entity(repositoryClass="Builder\PageBundle\Repository\PageRepository")
 */
class Page
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity="Application\Sonata\UserBundle\Entity\Group", inversedBy="pages", cascade={"persist"})
     * @ORM\JoinTable(name="app_pages_rights")
     */
    private $rights;

    /**
     * @var string
     *
     * @ORM\Column(name="class", type="string", length=255, nullable=true)
     */
    private $class = '';

    /**
     * @ORM\OneToMany(targetEntity="Builder\PageBundle\Entity\Page_Content", mappedBy="page", cascade={"persist"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $pageContents;

    /**
     * @var string
     *
     * @ORM\Column(name="seotitle", type="string", length=255, nullable=true)
     */
    private $seoTitle = '';

    /**
     * @var string
     *
     * @ORM\Column(name="seodesc", type="string", length=300, nullable=true)
     */
    private $seoDesc = '';

    /**
     * @var string
     *
     * @ORM\Column(name="seokeywords", type="string", length=300, nullable=true)
     */
    private $seokeywords = '';

    /**
     * @var string
     *
     * @ORM\Column(name="headerImage", type="string", length=300, nullable=true)
     */
    private $headerImage;

    /**
     * @var bool
     *
     * @ORM\Column(name="locked", type="boolean")
     */
    private $locked = false;
    
    public function __construct()
    {
        $this->rights = new ArrayCollection();
        $this->pageContents = new ArrayCollection();
    }

    public function getPageContents()
    {
        return $this->pageContents;
    }

    /**
     * Set menu pages
     *
     * @param string $pageContent
     *
     * @return Page Content
     */
    public function setPageContents($pageContents)
    {
        if (count($pageContents) > 0) {
            foreach ($pageContents as $i) {
                $this->addPageContent($i);
            }
        }

        return $this;
    }

    /**
     * Add pageContents
     *
     * @param \Builder\PageBundle\Entity\Menu_Page $pageContents
     *
     * @return Page
     */
    public function addPageContent(\Builder\PageBundle\Entity\Page_Content $pageContents)
    {
        $pageContents->setPage($this);
        $this->pageContents[] = $pageContents;

        return $this;
    }

    /**
     * Remove pageContents
     *
     * @param \Builder\PageBundle\Entity\Page_Content $pageContents
     */
    public function removePageContent(\Builder\PageBundle\Entity\Page_Content $pageContent)
    {
        $this->pageContents->removeElement($pageContent);
    }

    /**
     * @param Group[] $rights
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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Page
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
     *
     * @return Page
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
     * Set class
     *
     * @param string $class
     *
     * @return Page
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

    /**
     * Set seoTitle
     *
     * @param string $seoTitle
     *
     * @return Page
     */
    public function setSeoTitle($seoTitle)
    {
        $this->seoTitle = $seoTitle;

        return $this;
    }

    /**
     * Get seoTitle
     *
     * @return string
     */
    public function getSeoTitle()
    {
        return $this->seoTitle;
    }

    /**
     * Set seoDesc
     *
     * @param string $seoDesc
     *
     * @return Page
     */
    public function setSeoDesc($seoDesc)
    {
        $this->seoDesc = $seoDesc;

        return $this;
    }

    /**
     * Get seoDesc
     *
     * @return string
     */
    public function getSeoDesc()
    {
        return $this->seoDesc;
    }

    /**
     * Set seokeywords
     *
     * @param string $seokeywords
     *
     * @return Page
     */
    public function setSeoKeywords($seokeywords)
    {
        $this->seokeywords = $seokeywords;

        return $this;
    }

    /**
     * Get seokeywords
     *
     * @return string
     */
    public function getSeoKeywords()
    {
        return $this->seokeywords;
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
     * Set headerImage
     *
     * @param string $headerImage
     *
     * @return Page
     */
    public function setHeaderImage($headerImage)
    {
        $this->headerImage = $headerImage;

        return $this;
    }

    /**
     * Get headerImage
     *
     * @return string
     */
    public function getHeaderImage()
    {
        return $this->headerImage;
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
}

