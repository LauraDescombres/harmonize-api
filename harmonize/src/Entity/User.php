<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="user", indexes={@ORM\Index(columns={"username"}, flags={"fulltext"})})
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user_browse", "user_read", "user_account"})
     * @Groups({"project_browse", "project_read"})
     * @Groups({"message_browse", "message_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user_browse", "user_read", "user_account"})
     * @Groups({"project_browse", "project_read"})
     * @Groups({"message_browse", "message_read"})
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"user_password"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user_browse", "user_read", "user_account"})
     * @Groups({"project_read"})
     * @Groups({"message_browse", "message_read"})
     */
    private $picture;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"user_browse", "user_read", "user_account"})
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"user_browse", "user_read", "user_account"})
     */
    private $profil;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user_account"})
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"user_browse", "user_read", "user_account"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"user_browse", "user_read", "user_account"})
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity=Project::class, mappedBy="user")
     * @Groups({"user_read", "user_account"})
     */
    private $projects;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="user")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="sender")
     */
    private $message_sent;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="recipient")
     */
    private $message_received;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user_browse", "user_read", "user_account", "user_password"})
     * @Groups({"project_browse", "project_read"})
     * @Groups({"message_browse", "message_read"})
     */
    private $slug;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->message_sent = new ArrayCollection();
        $this->message_received = new ArrayCollection();
        $this->created_at = new \DateTime();
        $this->status = 1;
    }

    public function __toString()
    {
        return $this->username;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
        
    }
    
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;

        // return array_unique($roles);
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getProfil(): ?int
    {
        return $this->profil;
    }

    public function setProfil(int $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtDoctrineEvent(): self
    {
        $this->updated_at = new \DateTime();
        
        return $this;
    }

    /**
     * @return Collection|Project[]
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
            $project->setUser($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getUser() === $this) {
                $project->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessageSent(): Collection
    {
        return $this->message_sent;
    }

    public function addMessageSent(Message $messageSent): self
    {
        if (!$this->message_sent->contains($messageSent)) {
            $this->message_sent[] = $messageSent;
            $messageSent->setSender($this);
        }

        return $this;
    }

    public function removeMessageSent(Message $messageSent): self
    {
        if ($this->message_sent->removeElement($messageSent)) {
            // set the owning side to null (unless already changed)
            if ($messageSent->getSender() === $this) {
                $messageSent->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessageReceived(): Collection
    {
        return $this->message_received;
    }

    public function addMessageReceived(Message $messageReceived): self
    {
        if (!$this->message_received->contains($messageReceived)) {
            $this->message_received[] = $messageReceived;
            $messageReceived->setRecipient($this);
        }

        return $this;
    }

    public function removeMessageReceived(Message $messageReceived): self
    {
        if ($this->message_received->removeElement($messageReceived)) {
            // set the owning side to null (unless already changed)
            if ($messageReceived->getRecipient() === $this) {
                $messageReceived->setRecipient(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }
}
