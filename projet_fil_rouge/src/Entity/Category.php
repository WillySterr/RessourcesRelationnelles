<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @ApiResource()
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Articles::class, mappedBy="category")
     */
    private $articles;

    /**
     * @ORM\ManyToMany(targetEntity=Videos::class, mappedBy="category")
     */
    private $videos;

    /**
     * @ORM\ManyToMany(targetEntity=Photos::class, mappedBy="category")
     */
    private $photos;

    /**
     * @ORM\ManyToMany(targetEntity=Evenements::class, mappedBy="category")
     */
    private $evenements;

    /**
     * @ORM\ManyToMany(targetEntity=Informations::class, mappedBy="category")
     */
    private $informations;


    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->photos = new ArrayCollection();
        $this->evenements = new ArrayCollection();
        $this->informations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Articles[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Articles $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->addCategory($this);
        }

        return $this;
    }

    public function removeArticle(Articles $article): self
    {
        if ($this->articles->removeElement($article)) {
            $article->removeCategory($this);
        }

        return $this;
    }

    /**
     * @return Collection|Videos[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Videos $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->addCategory($this);
        }

        return $this;
    }

    public function removeVideo(Videos $video): self
    {
        if ($this->videos->removeElement($video)) {
            $video->removeCategory($this);
        }

        return $this;
    }

    /**
     * @return Collection|Photos[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photos $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->addCategory($this);
        }

        return $this;
    }

    public function removePhoto(Photos $photo): self
    {
        if ($this->photos->removeElement($photo)) {
            $photo->removeCategory($this);
        }

        return $this;
    }

    /**
     * @return Collection|Evenements[]
     */
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }

    public function addEvenement(Evenements $evenement): self
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements[] = $evenement;
            $evenement->addCategory($this);
        }

        return $this;
    }

    public function removeEvenement(Evenements $evenement): self
    {
        if ($this->evenements->removeElement($evenement)) {
            $evenement->removeCategory($this);
        }

        return $this;
    }

    /**
     * @return Collection|Informations[]
     */
    public function getInformations(): Collection
    {
        return $this->informations;
    }

    public function addInformation(Informations $information): self
    {
        if (!$this->informations->contains($information)) {
            $this->informations[] = $information;
            $information->addCategory($this);
        }

        return $this;
    }

    public function removeInformation(Informations $information): self
    {
        if ($this->informations->removeElement($information)) {
            $information->removeCategory($this);
        }

        return $this;
    }
}
