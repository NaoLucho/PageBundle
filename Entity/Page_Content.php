<?php

namespace Builder\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Page_Content
 *
 * @ORM\Table(name="app_page_content")
 * @ORM\Entity(repositoryClass="Builder\PageBundle\Repository\Page_ContentRepository")
 */
class Page_Content
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
     * @ORM\ManyToOne(targetEntity="Builder\PageBundle\Entity\Page", inversedBy="pageContents")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id", nullable=false)
     */
    private $page;

    /**
     * @ORM\ManyToOne(targetEntity="Builder\PageBundle\Entity\Content", inversedBy="pageContents")
     * @ORM\JoinColumn(name="content_id", referencedColumnName="id", nullable=false)
     * @ORM\OrderBy({"type" = "ASC"})
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="position", type="string", length=255)
     */
    private $position;


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
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param mixed $page
     *
     * @return $this
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set position
     *
     * @param string $position
     *
     * @return Page_Content
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }
}

