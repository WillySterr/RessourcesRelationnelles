<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommentsRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CommentsRepository::class)
 * @ApiResource(subresourceOperations={
 *           "api_ressources_comments_get_subresource" = {
 *               "normalization_context" = {"groups" = {"ressource_comments"}}
 *     }
 *     }, attributes={"order"={"createdAt": "DESC"}})
 */
class Comments
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("ressource_comments")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="comments")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Ressources::class, inversedBy="comments")
     */
    private $ressource;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("ressource_comments")
     * @var DateTime
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("ressource_comments")
     * @var DateTime
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("ressource_comments")
     */
    private $contenu;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     *  @Groups("ressource_comments")
     */
    public function getCommentUser()
    {
        return $this->getUser();
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

    public function getRessource(): ?Ressources
    {
        return $this->ressource;
    }

    public function setRessource(?Ressources $ressource): self
    {
        $this->ressource = $ressource;

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

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function __toString(){
        return $this->contenu;
    }
}
