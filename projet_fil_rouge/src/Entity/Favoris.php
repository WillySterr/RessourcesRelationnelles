<?php

namespace App\Entity;

use App\Repository\FavorisRepository;
use Doctrine\ORM\Mapping as ORM;
use Datetime;

/**
 * @ORM\Entity(repositoryClass=FavorisRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Favoris
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="favoris")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var Datetime
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Ressources::class, inversedBy="favoris")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ressource;

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

    public function getRessource(): ?Ressources
    {
        return $this->ressource;
    }

    public function setRessource(?Ressources $ressource): self
    {
        $this->ressource = $ressource;

        return $this;
    }
}
