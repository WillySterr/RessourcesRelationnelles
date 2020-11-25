<?php

namespace App\Entity;

use App\Repository\CommentairesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentairesRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Commentaires
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="commentaires")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Articles::class, inversedBy="commentaires")
     */
    private $articles;

    /**
     * @ORM\ManyToOne(targetEntity=Evenements::class, inversedBy="commentaires")
     */
    private $evenements;

    /**
     * @ORM\ManyToOne(targetEntity=Informations::class, inversedBy="commentaires")
     */
    private $informations;

    /**
     * @ORM\ManyToOne(targetEntity=Photos::class, inversedBy="commentaires")
     */
    private $photos;

    /**
     * @ORM\ManyToOne(targetEntity=Videos::class, inversedBy="commentaires")
     */
    private $videos;

    /**
     * @ORM\Column(type="boolean")
     */
    private $published;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contenu;

    /**
     * @var Datetime
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var Datetime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

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

    public function getArticles(): ?Articles
    {
        return $this->articles;
    }

    public function setArticles(?Articles $articles): self
    {
        $this->articles = $articles;

        return $this;
    }

    public function getEvenements(): ?Evenements
    {
        return $this->evenements;
    }

    public function setEvenements(?Evenements $evenements): self
    {
        $this->evenements = $evenements;

        return $this;
    }

    public function getInformations(): ?Informations
    {
        return $this->informations;
    }

    public function setInformations(?Informations $informations): self
    {
        $this->informations = $informations;

        return $this;
    }

    public function getPhotos(): ?Photos
    {
        return $this->photos;
    }

    public function setPhotos(?Photos $photos): self
    {
        $this->photos = $photos;

        return $this;
    }

    public function getVideos(): ?Videos
    {
        return $this->videos;
    }

    public function setVideos(?Videos $videos): self
    {
        $this->videos = $videos;

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

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

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
}
