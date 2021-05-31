<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="project", indexes={@ORM\Index(columns={"name", "description"}, flags={"fulltext"})})
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"project_browse", "project_read"})
     * @Groups({"user_read", "user_account"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"project_browse", "project_read"})
     * @Groups({"user_read", "user_account"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"project_browse", "project_read"})
     * @Groups({"user_read", "user_account"})
     */
    private $picture;

    /**
     * @ORM\Column(type="text")
     * @Groups({"project_browse", "project_read"})
     * @Groups({"user_read", "user_account"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"project_browse", "project_read"})
     * @Groups({"user_read", "user_account"})
     */
    private $audio_url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"project_browse", "project_read"})
     * @Groups({"user_read", "user_account"})
     */
    private $audio_url_ext;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"project_browse", "project_read"})
     * @Groups({"user_read", "user_account"})
     */
    private $status = 1;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"project_browse", "project_read"})
     * @Groups({"user_read", "user_account"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"project_browse", "project_read"})
     * @Groups({"user_read", "user_account"})
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity=MusicGenre::class, inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"project_browse", "project_read"})
     * @Groups({"user_read", "user_account"})
     */
    private $music_genre;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="project", orphanRemoval=true)
     * @Groups({"project_read"})
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"project_browse", "project_read"})
     */
    private $user;
  
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"project_browse", "project_read"})
     * @Groups({"user_read", "user_account"})
     */
    private $slug;


    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->status = 1;
        $this->comments = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAudioUrl(): ?string
    {
        return $this->audio_url;
    }

    public function setAudioUrl(?string $audio_url): self
    {
        $this->audio_url = $audio_url;

        return $this;
    }

    public function getAudioUrlExt(): ?string
    {
        return $this->audio_url_ext;
    }

    public function setAudioUrlExt(?string $audio_url_ext): self
    {
        $this->audio_url_ext = $audio_url_ext;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

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

    public function getMusicGenre(): ?MusicGenre
    {
        return $this->music_genre;
    }

    public function setMusicGenre(?MusicGenre $music_genre): self
    {
        $this->music_genre = $music_genre;

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
            $comment->setProject($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getProject() === $this) {
                $comment->setProject(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;

        return $this;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
}
