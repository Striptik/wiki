<?php

namespace Wiki\WikiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Page
 *
 * @ORM\Table(name="page")
 * @ORM\Entity(repositoryClass="Wiki\WikiBundle\Repository\PageRepository")
 */
class Page
{
    /**
     *
     * Identifiant unique d'une page
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *
     * Date de création de page
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="createdAt", type="datetime")
     */
    protected $createdAt;

    /**
     *
     * Date de mise à jour de la page
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updatedAt", type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     *
     * Titre de la page
     *
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     *
     * Slug généré à partir du titre de la page
     *
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", length=255, unique=true, nullable=true)
     */
    protected $slug;

    /**
     * Révisions de la page
     *
     * @ORM\OneToMany(targetEntity="PageRevision", mappedBy="page")
     */
    protected $revisions;


    /**
     *
     * Note moyenne de la page
     *
     * @ORM\OneToMany(targetEntity="Rating", mappedBy="page")
     */
    protected $ratings;


    /**
     *
     * Utilisaeur ayant créer la page
     *
     *  @ORM\ManyToOne(targetEntity="User", inversedBy="page")
     */
    private $user;



    public function __construct($user)
    {
        $this->revisions = new ArrayCollection();
        $this->setUser($user);

    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }



    /**
     * @return mixed
     */
    public function getRatings()
    {
        return $this->ratings;
    }

    /**
     * @param mixed $ratings
     */
    public function setRatings($ratings)
    {
        $this->ratings = $ratings;
    }


    /**
     * Set title
     *
     * @param string $title
     *
     * @return Page
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
     * @return mixed
     */
    public function getRevisions()
    {
        return $this->revisions;
    }

    /**
     * @param mixed $revisions
     */
    public function setRevisions($revisions)
    {
        $this->revisions = $revisions;
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Page
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Page
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
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
}

