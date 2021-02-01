<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AvatarsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use DateTime;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=AvatarsRepository::class)
 * @ApiResource(normalizationContext={"groups"={"fil_actu"}})
 * @Vich\Uploadable
 * @ORM\HasLifecycleCallbacks()

 */
class Avatars
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("fil_actu")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("fil_actu")
     * @Groups("ressource_comments")
     */
    private $avatarIcon;

    /**
     * @ORM\OneToMany(targetEntity=Users::class, mappedBy="avatar")
     */
    private $users;

    /**
     * @var File
     * @Vich\UploadableField(mapping="vichFiles", fileNameProperty="avatarIcon")
     * @Assert\File(
     *      maxSize = "1500k",
     *      mimeTypes = {"application/jpg", "application/jpeg", "application/png", "image/png", "image/jpg"},
     *      mimeTypesMessage = "Veuillez sélectionner une image au format 'jpg' ou 'png'."
     * )
     */
    private $avatarsFile;

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

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAvatarIcon(): ?string
    {
        return $this->avatarIcon;
    }

    public function setAvatarIcon(string $avatarIcon): self
    {
        $this->avatarIcon = $avatarIcon;

        return $this;
    }

    /**
     * @return Collection|Users[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setAvatar($this);
        }

        return $this;
    }

    public function removeUser(Users $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getAvatar() === $this) {
                $user->setAvatar(null);
            }
        }

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
    public function getAvatarsFile(): ?File
    {
        return $this->avatarsFile;
    }

    /**
     * @param File|null $avatarsFile
     */
    public function setAvatarsFile(?File $avatarsFile = null)
    {
        $this->avatarsFile = $avatarsFile;

        if ($avatarsFile !== null) {
              $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    public function __toString(){
        return '<img src="{{asset("uploads/avatars/'.$this->avatarIcon.'")}}"/>';
    }
}
