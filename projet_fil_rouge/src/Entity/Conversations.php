<?php

namespace App\Entity;

use App\Repository\ConversationsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConversationsRepository::class)
 */
class Conversations
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Users::class, inversedBy="conversations")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Messages::class, mappedBy="conversation", orphanRemoval=true)
     */
    private $messages;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastMessage;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastMessageDate;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
        }

        return $this;
    }

    public function removeUser(Users $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    /**
     * @return Collection|Messages[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Messages $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setConversation($this);
        }

        return $this;
    }

    public function removeMessage(Messages $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getConversation() === $this) {
                $message->setConversation(null);
            }
        }

        return $this;
    }

    public function getLastMessage(): ?string
    {
        return $this->lastMessage;
    }

    public function setLastMessage(?string $lastMessage): self
    {
        $this->lastMessage = $lastMessage;

        return $this;
    }

    public function getLastMessageDate(): ?\DateTimeInterface
    {
        return $this->lastMessageDate;
    }

    public function setLastMessageDate(?\DateTimeInterface $lastMessageDate): self
    {
        $this->lastMessageDate = $lastMessageDate;

        return $this;
    }
}
