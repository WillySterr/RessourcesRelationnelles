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
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=ArticlesRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @ApiResource(normalizationContext={"groups"={"fil_actu"}})
 * @Vich\Uploadable
 */
class Articles
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("fil_actu")
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
     * @Assert\NotNull
	 * @Assert\NotBlank
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("fil_actu")
     * @Assert\NotNull
	 * @Assert\NotBlank
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="articles")
     * @Groups("fil_actu")
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
     * @Groups("fil_actu")
     */
    private $video;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("fil_actu")
     */
    private $photo;

    /**
     * @var File
     * @Vich\UploadableField(mapping="vichFiles", fileNameProperty="photo")
     * @Assert\File(
     *      maxSize = "1500k",
     *      mimeTypes = {"application/jpg", "application/jpeg", "application/png"},
     *      mimeTypesMessage = "Veuillez sélectionner une image au format 'jpg' ou 'png'."
     * )
     */
    private $photoFile;

    /**
     * @var File
     * @Vich\UploadableField(mapping="vichFiles", fileNameProperty="video")
     * @Assert\File(
     *      maxSize = "5M",
     *      mimeTypes = {"application/mp4", "application/avi"},
     *      mimeTypesMessage = "Veuillez sélectionner une image au format 'mp4' ou 'avi'."
     * )
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

    public function setVideo($video): self

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


   
    public function setPhoto($photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getPhotoFile(): ?File
    {
        return $this->photoFile;
    }

    /**
     * @param File|null $photoFile
     */
    public function setPhotoFile(?File $photoFile = null)
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
    public function getVideoFile(): ?File
    {
        return $this->videoFile;
    }

    /**
     * @param File|null $videoFile
     */
    public function setVideoFile(?File $videoFile = null)
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
