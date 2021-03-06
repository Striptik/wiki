<?php

namespace Wiki\WikiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * PageRevision
 *
 * @ORM\Table(name="page_revision")
 * @ORM\Entity(repositoryClass="Wiki\WikiBundle\Repository\PageRevisionRepository")
 */
class PageRevision
{
    /**
     *
     * Identifiant unique d'une révision
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * État de la révision : [online, pending_validation, canceled, draft]
     *
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * Contenu de la révision
     *
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     *
     * Id de l'utilisateur ayant mis à jour
     *
     * @var string
     *
     * @ORM\Column(name="updatedBy", type="string", length=255, nullable=true)
     */
    private $updatedBy;

    /**
     *
     * Date de création de la révision
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     *
     * Page lié à la révision
     *
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="pageRevision")
     */
    private $page;

    public function __construct($page)
    {
        $this->setPage($page);
    }

    /**
     * @return string
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param string $page
     */
    public function setPage($page)
    {
        $this->page = $page;
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
     * Set status
     *
     * @param string $status
     *
     * @return PageRevision
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return PageRevision
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
     * Set updatedBy
     *
     * @param string $updatedBy
     *
     * @return PageRevision
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Get updatedBy
     *
     * @return string
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return PageRevision
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set revisionVersion
     *
     * @param integer $revisionVersion
     *
     * @return PageRevision
     */
    public function setRating($revisionVersion)
    {
        $this->revisionVersion = $revisionVersion;

        return $this;
    }

}

