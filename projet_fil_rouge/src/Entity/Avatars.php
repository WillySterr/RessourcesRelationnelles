<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AvatarsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=AvatarsRepository::class)
 * @ApiResource(normalizationContext={"groups"={"fil_actu"}})
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

    public function __toString(){
        return '<img src="{{asset("uploads/avatars/'.$this->avatarIcon.'")}}"/>';
    }
}
