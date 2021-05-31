<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"message_browse", "message_read", "message_edit"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"message_browse", "message_read"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"message_browse", "message_read"})
     */
    private $message;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"message_browse", "message_read", "message_edit"})
     */
    private $is_read;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"message_browse", "message_read"})
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="message_sent")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"message_browse", "message_read"})
     */
    private $sender;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="message_received")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"message_browse", "message_read"})
     */
    private $recipient;


    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->is_read = false;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getIsRead(): ?bool
    {
        return $this->is_read;
    }

    public function setIsRead(bool $is_read): self
    {
        $this->is_read = $is_read;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getRecipient(): ?User
    {
        return $this->recipient;
    }

    public function setRecipient(?User $recipient): self
    {
        $this->recipient = $recipient;

        return $this;
    }
}
