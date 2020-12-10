<?php

namespace App\Entity;

use App\Repository\RessourcesRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     * @var DateTime
     */
    private $createdAt;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTime
     */
    private $updatedAt;

    /**
     * @var DateTime
     * @ORM\Column(type="boolean")
     */
    private $published;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="ressource")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=Favoris::class, mappedBy="ressource", orphanRemoval=true)
     */
    private $favoris;
    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->favoris = new ArrayCollection();
    }

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
    public function setCreatedAt()
    {
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
    public function setUpdatedAt()
    {
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setRessource($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getRessource() === $this) {
                $comment->setRessource(null);
            }
        }

        return $this;
    }




    /**
     * @return Collection|Favoris[]
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(Favoris $favori): self
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris[] = $favori;
            $favori->setRessource($this);
        }

        return $this;
    }

    public function removeFavori(Favoris $favori): self
    {
        if ($this->favoris->removeElement($favori)) {
            // set the owning side to null (unless already changed)
            if ($favori->getRessource() === $this) {
                $favori->setRessource(null);
            }
        }

        return $this;
    }
}
