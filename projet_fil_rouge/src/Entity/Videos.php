<?php

namespace App\Entity;

use App\Repository\VideosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use DateTime;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * @ORM\Entity(repositoryClass=VideosRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 * @ApiResource(normalizationContext={"groups"={"fil_actu"}})
 */
class Videos
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("fil_actu")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="videos")
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $published;
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("fil_actu")
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
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="videos")
     * @Groups("fil_actu")
     */
    private $category;

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

  
    /**
     * @var File
     * @Vich\UploadableField(mapping="vichFiles", fileNameProperty="mediaFile")
     * @Assert\File(
     *      maxSize = "5M",
     *      mimeTypes = {"application/mp4", "application/avi"},
     *      mimeTypesMessage = "Veuillez sélectionner une image au format 'mp4' ou 'avi'."
     * )
     */
    private $vichFile;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("fil_actu")
     */
    private $mediaFile;


    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
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
     * @return File|null
     */
    public function getVichFile(): ?File
    {
        return $this->vichFile;
    }

    /**
     * @param File|null $vichFile
     */
    public function setVichFile(?File $vichFile = null)
    {
        $this->vichFile = $vichFile;

        if ($vichFile !== null) {
              $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    public function __toString(){
        return $this->titre;
    }

    public function getMediaFile(): ?string
    {
        return $this->mediaFile;
    }

    public function setMediaFile($mediaFile): self
    {
        $this->mediaFile = $mediaFile;

        return $this;
    }
}
