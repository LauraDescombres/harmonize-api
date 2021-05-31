<?php

namespace App\Entity;

use App\Repository\MusicGenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=MusicGenreRepository::class)
 */
class MusicGenre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"project_browse", "project_read"})
     * @Groups({"genre_browse"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"genre_browse"})
     * @Groups({"project_browse", "project_read"})
     * @Groups({"user_read", "user_account"})
     * 
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Project::class, mappedBy="music_genre")
     */
    private $projects;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
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
            $project->setMusicGenre($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getMusicGenre() === $this) {
                $project->setMusicGenre(null);
            }
        }

        return $this;
    }
}
