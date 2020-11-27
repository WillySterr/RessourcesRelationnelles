<?php

namespace App\Entity;

use App\Repository\RessourcesRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity(repositoryClass=RessourcesRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Ressources
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="ressources")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity=Articles::class, cascade={"persist", "remove"})
     */
    private $article;

    /**
     * @ORM\OneToOne(targetEntity=Evenements::class, cascade={"persist", "remove"})
     */
    private $evenement;

    /**
     * @ORM\OneToOne(targetEntity=Informations::class, cascade={"persist", "remove"})
     */
    private $information;

    /**
     * @ORM\OneToOne(targetEntity=Photos::class, cascade={"persist", "remove"})
     */
    private $photo;

    /**
     * @ORM\OneToOne(targetEntity=Videos::class, cascade={"persist", "remove"})
     */
    private $video;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var DateTime
     * @ORM\Column(type="boolean")
     */
    private $published;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getArticle(): ?Articles
    {
        return $this->article;
    }

    public function setArticle(?Articles $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getEvenement(): ?Evenements
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenements $evenement): self
    {
        $this->evenement = $evenement;

        return $this;
    }

    public function getInformation(): ?Informations
    {
        return $this->information;
    }

    public function setInformation(?Informations $information): self
    {
        $this->information = $information;

        return $this;
    }

    public function getPhoto(): ?Photos
    {
        return $this->photo;
    }

    public function setPhoto(?Photos $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getVideo(): ?Videos
    {
        return $this->video;
    }

    public function setVideo(?Videos $video): self
    {
        $this->video = $video;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAt() {
        try {
            $this->createdAt = new DateTime('now', new \DateTimeZone("Europe/Paris"));
        } catch (\Exception $e) {
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAt() {
        try {
            $this->updatedAt = new DateTime('now', new \DateTimeZone("Europe/Paris"));
        } catch (\Exception $e) {
        }

        return $this;
    }

    public function getPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): self
    {
        $this->published = $published;

        return $this;
    }
}
