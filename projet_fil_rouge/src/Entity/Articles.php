<?php

namespace App\Entity;

use App\Repository\ArticlesRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=ArticlesRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @ApiResource()
 * @Vich\Uploadable
 */
class Articles
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="articles")
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $published;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="articles")
     */
    private $category;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTime
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $video;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @var File
     * @Vich\UploadableField(mapping="vichFiles", fileNameProperty="photo")
     */
    private $photoFile;

    /**
     * @var File
     * @Vich\UploadableField(mapping="vichFiles", fileNameProperty="video")
     */
    private $videoFile;

    public function __construct()
    {
        $this->category = new ArrayCollection();
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
    public function getPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): self
    {
        $this->published = $published;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

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
     * @return Collection|Category[]
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->category->removeElement($category);

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


    /**
     * @return string|null
     */
    public function getVideo(): ?string
    {
        return $this->video;
    }


    /**
     * @param string|null $video
     * @return $this
     */
    public function setVideo(string $video): self
    {
        $this->video = $video;

        return $this;
    }


    /**
     * @return string|null
     */
    public function getPhoto(): ?string
    {
        return $this->photo;
    }


    /**
     * @param string|null $photo
     * @return $this
     */
    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getphotoFile(): ?File
    {
        return $this->photoFile;
    }

    /**
     * @param File|null $photoFile
     */
    public function setphotoFile(?File $photoFile = null)
    {
        $this->photoFile = $photoFile;

        if ($photoFile !== null) {
              $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getvideoFile(): ?File
    {
        return $this->videoFile;
    }

    /**
     * @param File|null $videoFile
     */
    public function setvideoFile(?File $videoFile = null)
    {
        $this->videoFile = $videoFile;

        if ($videoFile !== null) {
              $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    public function __toString(){
        return $this->titre;
    }
}
